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
document.addEventListener('DOMContentLoaded', function() {
    // ─── Constants & State ────────────────────────────────────────
    var API = '/api/v1';
    var TOKEN = localStorage.getItem('wc_token');
    var editId = null;
    var slugManuallyEdited = false;

    // Parse ?id=X from URL
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('id')) {
        editId = urlParams.get('id');
    }

    // ─── Toast Helper ─────────────────────────────────────────────
    function showToast(message, type) {
        type = type || 'success';
        var colors = { success: '#28a745', danger: '#dc3545', warning: '#ffc107', info: '#17a2b8' };
        var toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;padding:12px 24px;border-radius:8px;color:#fff;font-size:14px;font-weight:500;box-shadow:0 4px 12px rgba(0,0,0,0.15);transition:opacity 0.3s;background:' + (colors[type] || colors.success);
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(function() { toast.style.opacity = '0'; }, 2500);
        setTimeout(function() { toast.remove(); }, 3000);
    }

    // ─── Initialize Quill Editor ──────────────────────────────────
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

    // ─── DOM Elements ─────────────────────────────────────────────
    var titleInput = document.getElementById('articleTitle');
    var slugInput = document.getElementById('articleSlug');
    var categorySelect = document.getElementById('articleCategory');
    var languageSelect = document.getElementById('articleLanguage');
    var statusSelect = document.getElementById('articleStatus');
    var excerptInput = document.getElementById('articleExcerpt');
    var sourceUrlInput = document.getElementById('sourceUrl');
    var metaTitleInput = document.getElementById('metaTitle');
    var metaDescInput = document.getElementById('metaDescription');
    var focusKeywordInput = document.getElementById('focusKeyword');
    var fileInput = document.getElementById('featuredImage');
    var uploadArea = document.getElementById('imageUploadArea');
    var imagePreview = document.getElementById('imagePreview');
    var previewImg = document.getElementById('previewImg');

    // Buttons
    var saveDraftBtn = document.querySelector('.card-footer .btn-subtle-secondary');
    var publishBtn = document.querySelector('.card-footer .btn-primary');

    // ─── Auto-generate slug from title ────────────────────────────
    titleInput.addEventListener('input', function() {
        if (!slugManuallyEdited) {
            var slug = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '')
                .substring(0, 80);
            slugInput.value = slug;
        }
    });

    slugInput.addEventListener('input', function() {
        slugManuallyEdited = true;
    });

    document.getElementById('regenerateSlug')?.addEventListener('click', function() {
        slugManuallyEdited = false;
        titleInput.dispatchEvent(new Event('input'));
    });

    // ─── Show/hide schedule date based on status ──────────────────
    statusSelect.addEventListener('change', function() {
        var wrapper = document.getElementById('scheduleDateWrapper');
        wrapper.style.display = this.value === 'scheduled' ? 'block' : 'none';
    });

    // ─── SEO character counters ───────────────────────────────────
    metaTitleInput.addEventListener('input', function() {
        document.getElementById('metaTitleCount').textContent = this.value.length + ' / 60';
    });
    metaDescInput.addEventListener('input', function() {
        document.getElementById('metaDescCount').textContent = this.value.length + ' / 160';
    });

    // ─── Image upload / drag & drop ───────────────────────────────
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

    // ─── AI Rewrite button (placeholder) ──────────────────────────
    document.getElementById('aiRewriteBtn')?.addEventListener('click', function() {
        var btn = this;
        var originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Rewriting...';
        btn.disabled = true;
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

    // ─── API: Build FormData ──────────────────────────────────────
    function buildFormData(status) {
        var fd = new FormData();
        fd.append('title', titleInput.value.trim());
        fd.append('slug', slugInput.value.trim());
        fd.append('content', quill.root.innerHTML);
        fd.append('excerpt', excerptInput.value.trim());
        fd.append('category', categorySelect.value || '');
        fd.append('language', languageSelect.value || 'pl');
        fd.append('status', status);
        fd.append('source_url', sourceUrlInput.value.trim());
        fd.append('meta_title', metaTitleInput.value.trim());
        fd.append('meta_description', metaDescInput.value.trim());
        fd.append('focus_keyword', focusKeywordInput.value.trim());

        if (fileInput.files.length) {
            fd.append('featured_image', fileInput.files[0]);
        }

        return fd;
    }

    // ─── API: Send request ────────────────────────────────────────
    function apiRequest(method, url, formData) {
        var headers = {
            'Accept': 'application/json',
            'Authorization': 'Bearer ' + TOKEN
        };
        // Do NOT set Content-Type — browser will set multipart/form-data with boundary

        return fetch(url, {
            method: method,
            headers: headers,
            body: formData
        });
    }

    function saveArticle(status) {
        // Validation
        if (!titleInput.value.trim()) {
            showToast('Title is required', 'danger');
            titleInput.focus();
            return;
        }
        if (quill.getText().trim().length < 10) {
            showToast('Content is too short', 'danger');
            return;
        }

        var fd = buildFormData(status);
        var url, method;

        if (editId) {
            // Update existing article — use POST with _method=PUT for FormData
            fd.append('_method', 'PUT');
            url = API + '/news/articles/' + editId;
            method = 'POST';
        } else {
            // Create new article
            url = API + '/news/articles';
            method = 'POST';
        }

        // Disable buttons during save
        saveDraftBtn.disabled = true;
        publishBtn.disabled = true;
        var activeBtn = (status === 'draft') ? saveDraftBtn : publishBtn;
        var originalBtnHtml = activeBtn.innerHTML;
        activeBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Saving...';

        apiRequest(method, url, fd)
            .then(function(response) {
                if (response.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                return response.json().then(function(data) {
                    return { ok: response.ok, status: response.status, data: data };
                });
            })
            .then(function(result) {
                if (!result) return;

                if (result.ok) {
                    var msg = editId ? 'Article updated successfully!' : 'Article created successfully!';
                    showToast(msg, 'success');
                    // If newly created, store the ID in case of further edits
                    if (!editId && result.data && result.data.data && result.data.data.id) {
                        editId = result.data.data.id;
                    } else if (!editId && result.data && result.data.id) {
                        editId = result.data.id;
                    }
                    // Redirect to articles list after a short delay
                    setTimeout(function() {
                        window.location.href = '/content-articles';
                    }, 800);
                } else {
                    // Handle validation errors
                    var errorMsg = 'Failed to save article';
                    if (result.data && result.data.message) {
                        errorMsg = result.data.message;
                    }
                    if (result.data && result.data.errors) {
                        var firstKey = Object.keys(result.data.errors)[0];
                        if (firstKey) {
                            errorMsg = result.data.errors[firstKey][0];
                        }
                    }
                    showToast(errorMsg, 'danger');
                }
            })
            .catch(function(err) {
                console.error('Save error:', err);
                showToast('Network error. Please try again.', 'danger');
            })
            .finally(function() {
                saveDraftBtn.disabled = false;
                publishBtn.disabled = false;
                activeBtn.innerHTML = originalBtnHtml;
            });
    }

    // ─── Button Click Handlers ────────────────────────────────────
    saveDraftBtn.addEventListener('click', function(e) {
        e.preventDefault();
        saveArticle('draft');
    });

    publishBtn.addEventListener('click', function(e) {
        e.preventDefault();
        saveArticle('published');
    });

    // Prevent native form submission
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveArticle('published');
        });
    }

    // ─── Load Article for Editing ─────────────────────────────────
    function loadArticle(id) {
        fetch(API + '/news/articles/' + id, {
            headers: {
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + TOKEN
            }
        })
        .then(function(response) {
            if (response.status === 401) {
                window.location.href = '/login';
                return null;
            }
            if (!response.ok) {
                showToast('Failed to load article', 'danger');
                return null;
            }
            return response.json();
        })
        .then(function(json) {
            if (!json) return;
            var article = json.data || json;

            // Populate form fields
            titleInput.value = article.title || '';
            slugInput.value = article.slug || '';
            slugManuallyEdited = true; // Don't auto-overwrite loaded slug

            if (article.content) {
                quill.root.innerHTML = article.content;
            }
            excerptInput.value = article.excerpt || '';

            if (article.category) {
                categorySelect.value = article.category;
            }
            if (article.language) {
                languageSelect.value = article.language;
            }
            if (article.status) {
                statusSelect.value = article.status;
                statusSelect.dispatchEvent(new Event('change'));
            }
            if (article.source_url) {
                sourceUrlInput.value = article.source_url;
            }
            if (article.meta_title) {
                metaTitleInput.value = article.meta_title;
                metaTitleInput.dispatchEvent(new Event('input'));
            }
            if (article.meta_description) {
                metaDescInput.value = article.meta_description;
                metaDescInput.dispatchEvent(new Event('input'));
            }
            if (article.focus_keyword) {
                focusKeywordInput.value = article.focus_keyword;
            }

            // Show existing featured image if present
            if (article.featured_image || article.featured_image_url) {
                var imgUrl = article.featured_image_url || article.featured_image;
                previewImg.src = imgUrl;
                imagePreview.classList.remove('d-none');
                uploadArea.classList.add('d-none');
            }

            // Update page title to reflect edit mode
            var pageTitle = document.querySelector('.sub-title, [data-lang="wc-create-article"]');
            if (pageTitle) {
                pageTitle.textContent = 'Edit Article';
            }
            document.title = 'Edit Article | WinCase CRM';
        })
        .catch(function(err) {
            console.error('Load error:', err);
            showToast('Error loading article data', 'danger');
        });
    }

    // ─── Init: Load article if editing ────────────────────────────
    if (editId) {
        loadArticle(editId);
    }
});
</script>
@endsection
