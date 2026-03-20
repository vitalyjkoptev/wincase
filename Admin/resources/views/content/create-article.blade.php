@extends('partials.layouts.master')
@section('title', 'Create Article | WinCase CRM')
@section('sub-title', 'Create Article')
@section('sub-title-lang', 'wc-create-article')
@section('pagetitle', 'Content')
@section('pagetitle-lang', 'wc-content')

@section('css')
<link href="{{ asset('assets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css">
<style>
    .ql-editor {
        min-height: 400px;
        font-size: 15px;
        line-height: 1.7;
    }
    .slug-field {
        font-family: monospace;
        font-size: 13px;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<form action="#" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Left Column: Editor -->
        <div class="col-xl-8">
            <!-- Title -->
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="articleTitle" class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg" id="articleTitle" placeholder="Enter article title..." required>
                    </div>
                    <div class="mb-0">
                        <label for="articleSlug" class="form-label fw-semibold">Slug</label>
                        <div class="input-group">
                            <span class="input-group-text fs-13">/articles/</span>
                            <input type="text" class="form-control slug-field" id="articleSlug" placeholder="auto-generated-slug">
                            <button type="button" class="btn btn-subtle-secondary btn-sm" id="regenerateSlug" title="Regenerate slug">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Editor -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-quill-pen-line me-2"></i>Content <span class="text-danger">*</span></h5>
                </div>
                <div class="card-body p-0">
                    <div id="editor"></div>
                </div>
            </div>

            <!-- Excerpt -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-text me-2"></i>Excerpt</h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="articleExcerpt" rows="3" placeholder="Short description of the article for previews and SEO..."></textarea>
                    <div class="form-text mt-1">Recommended: 150-160 characters for optimal display in search results.</div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="col-xl-4">
            <!-- Publish -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-send-plane-line me-2"></i>Publish</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="articleStatus" class="form-label">Status</label>
                        <select class="form-select" id="articleStatus">
                            <option value="draft" selected>Draft</option>
                            <option value="review">Review</option>
                            <option value="published">Published</option>
                            <option value="scheduled">Scheduled</option>
                        </select>
                    </div>
                    <div class="mb-3" id="scheduleDateWrapper" style="display: none;">
                        <label for="publishDate" class="form-label">Publish Date</label>
                        <input type="datetime-local" class="form-control" id="publishDate">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Visibility</label>
                        <select class="form-select">
                            <option value="public" selected>Public</option>
                            <option value="private">Private</option>
                            <option value="password">Password Protected</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <button type="button" class="btn btn-subtle-secondary flex-fill">
                        <i class="ri-save-line me-1"></i>Save Draft
                    </button>
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="ri-send-plane-line me-1"></i>Publish
                    </button>
                </div>
            </div>

            <!-- Category -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-folder-line me-2"></i>Category</h5>
                </div>
                <div class="card-body">
                    <select class="form-select" id="articleCategory">
                        <option selected disabled>Select category...</option>
                        <option value="immigration">Immigration</option>
                        <option value="work-permits">Work Permits</option>
                        <option value="residence">Residence</option>
                        <option value="tax">Tax & Accounting</option>
                        <option value="company-registration">Company Registration</option>
                        <option value="legal-updates">Legal Updates</option>
                        <option value="guides">Guides</option>
                    </select>
                </div>
            </div>

            <!-- Language & AI -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-translate-2 me-2"></i>Language & Source</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="articleLanguage" class="form-label">Language <span class="text-danger">*</span></label>
                        <select class="form-select" id="articleLanguage">
                            <option value="pl" selected>&#127477;&#127473; Polish</option>
                            <option value="ua">&#127482;&#127462; Ukrainian</option>
                            <option value="ru">&#127479;&#127482; Russian</option>
                            <option value="en">&#127468;&#127463; English</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="sourceUrl" class="form-label">Source URL</label>
                        <input type="url" class="form-control" id="sourceUrl" placeholder="https://rss-source.com/article...">
                        <div class="form-text">RSS source for parsed articles</div>
                    </div>
                    <button type="button" class="btn btn-subtle-primary w-100" id="aiRewriteBtn">
                        <i class="ri-robot-line me-1"></i>AI Rewrite Content
                    </button>
                </div>
            </div>

            <!-- SEO -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-search-eye-line me-2"></i>SEO</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="metaTitle" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" id="metaTitle" placeholder="SEO title (max 60 chars)..." maxlength="60">
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted" id="metaTitleCount">0 / 60</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="metaDescription" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="metaDescription" rows="3" placeholder="SEO description (max 160 chars)..." maxlength="160"></textarea>
                        <div class="d-flex justify-content-end mt-1">
                            <small class="text-muted" id="metaDescCount">0 / 160</small>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label for="focusKeyword" class="form-label">Focus Keyword</label>
                        <input type="text" class="form-control" id="focusKeyword" placeholder="e.g. karta pobytu, pozwolenie na prac&#281;...">
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-image-line me-2"></i>Featured Image</h5>
                </div>
                <div class="card-body">
                    <div class="border border-dashed rounded-3 text-center p-4" id="imageUploadArea" style="cursor: pointer;">
                        <input type="file" class="d-none" id="featuredImage" accept="image/*">
                        <i class="ri-upload-cloud-2-line display-5 text-muted"></i>
                        <p class="text-muted mb-0 mt-2">Click or drag to upload</p>
                        <p class="text-muted fs-12 mb-0">JPG, PNG, WebP (max 2MB)</p>
                    </div>
                    <div id="imagePreview" class="mt-3 d-none">
                        <img src="" alt="Preview" class="img-fluid rounded-3" id="previewImg">
                        <button type="button" class="btn btn-sm btn-subtle-danger mt-2 w-100" id="removeImage">
                            <i class="ri-delete-bin-line me-1"></i>Remove Image
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('js')
<script src="{{ asset('assets/libs/quill/quill.min.js') }}"></script>
<script>
    // Initialize Quill Editor
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Write your article content here...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, false] }],
                [{ 'font': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean']
            ]
        }
    });

    // Auto-generate slug from title
    var titleInput = document.getElementById('articleTitle');
    var slugInput = document.getElementById('articleSlug');

    titleInput.addEventListener('input', function() {
        var slug = this.value
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_]+/g, '-')
            .replace(/^-+|-+$/g, '')
            .substring(0, 80);
        slugInput.value = slug;
    });

    document.getElementById('regenerateSlug')?.addEventListener('click', function() {
        titleInput.dispatchEvent(new Event('input'));
    });

    // Show/hide schedule date based on status
    document.getElementById('articleStatus').addEventListener('change', function() {
        var wrapper = document.getElementById('scheduleDateWrapper');
        wrapper.style.display = this.value === 'scheduled' ? 'block' : 'none';
    });

    // SEO character counters
    document.getElementById('metaTitle').addEventListener('input', function() {
        document.getElementById('metaTitleCount').textContent = this.value.length + ' / 60';
    });
    document.getElementById('metaDescription').addEventListener('input', function() {
        document.getElementById('metaDescCount').textContent = this.value.length + ' / 160';
    });

    // Image upload
    var uploadArea = document.getElementById('imageUploadArea');
    var fileInput = document.getElementById('featuredImage');
    var imagePreview = document.getElementById('imagePreview');
    var previewImg = document.getElementById('previewImg');

    uploadArea.addEventListener('click', function() {
        fileInput.click();
    });

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-primary');
    });
    uploadArea.addEventListener('dragleave', function() {
        this.classList.remove('border-primary');
    });
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-primary');
        if (e.dataTransfer.files.length) {
            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', function() {
        if (this.files.length) {
            showPreview(this.files[0]);
        }
    });

    function showPreview(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            imagePreview.classList.remove('d-none');
            uploadArea.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }

    document.getElementById('removeImage')?.addEventListener('click', function() {
        fileInput.value = '';
        previewImg.src = '';
        imagePreview.classList.add('d-none');
        uploadArea.classList.remove('d-none');
    });

    // AI Rewrite button
    document.getElementById('aiRewriteBtn')?.addEventListener('click', function() {
        var btn = this;
        var originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Rewriting...';
        btn.disabled = true;
        // Simulate AI rewrite delay
        setTimeout(function() {
            btn.innerHTML = '<i class="ri-check-line me-1"></i>Content Rewritten!';
            btn.classList.remove('btn-subtle-primary');
            btn.classList.add('btn-subtle-success');
            setTimeout(function() {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-subtle-success');
                btn.classList.add('btn-subtle-primary');
                btn.disabled = false;
            }, 2000);
        }, 3000);
    });
</script>
@endsection
