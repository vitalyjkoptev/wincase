<?php

namespace App\Enums;

enum CaseStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in_progress';
    case DOCUMENTS_COLLECTING = 'documents_collecting';
    case DOCUMENTS_REVIEW = 'documents_review';
    case SUBMISSION_PREPARATION = 'submission_preparation';
    case SUBMITTED_TO_OFFICE = 'submitted_to_office';
    case WAITING_FOR_DECISION = 'waiting_for_decision';
    case ADDITIONAL_DOCUMENTS_REQUESTED = 'additional_documents_requested';
    case POSITIVE_DECISION = 'positive_decision';
    case NEGATIVE_DECISION = 'negative_decision';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'New',
            self::IN_PROGRESS => 'In Progress',
            self::DOCUMENTS_COLLECTING => 'Documents Collecting',
            self::DOCUMENTS_REVIEW => 'Documents Review',
            self::SUBMISSION_PREPARATION => 'Submission Preparation',
            self::SUBMITTED_TO_OFFICE => 'Submitted to Office',
            self::WAITING_FOR_DECISION => 'Waiting for Decision',
            self::ADDITIONAL_DOCUMENTS_REQUESTED => 'Additional Documents Requested',
            self::POSITIVE_DECISION => 'Positive Decision',
            self::NEGATIVE_DECISION => 'Negative Decision',
            self::COMPLETED => 'Completed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => '#3B82F6',
            self::IN_PROGRESS => '#F59E0B',
            self::DOCUMENTS_COLLECTING => '#8B5CF6',
            self::DOCUMENTS_REVIEW => '#6366F1',
            self::SUBMISSION_PREPARATION => '#EC4899',
            self::SUBMITTED_TO_OFFICE => '#14B8A6',
            self::WAITING_FOR_DECISION => '#F97316',
            self::ADDITIONAL_DOCUMENTS_REQUESTED => '#EF4444',
            self::POSITIVE_DECISION => '#10B981',
            self::NEGATIVE_DECISION => '#DC2626',
            self::COMPLETED => '#059669',
        };
    }

    public function isActive(): bool
    {
        return !in_array($this, [self::COMPLETED, self::POSITIVE_DECISION, self::NEGATIVE_DECISION]);
    }

    public function progress(): int
    {
        return match ($this) {
            self::NEW => 5,
            self::IN_PROGRESS => 15,
            self::DOCUMENTS_COLLECTING => 25,
            self::DOCUMENTS_REVIEW => 40,
            self::SUBMISSION_PREPARATION => 55,
            self::SUBMITTED_TO_OFFICE => 70,
            self::WAITING_FOR_DECISION => 80,
            self::ADDITIONAL_DOCUMENTS_REQUESTED => 60,
            self::POSITIVE_DECISION => 95,
            self::NEGATIVE_DECISION => 100,
            self::COMPLETED => 100,
        };
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Enum статусов дела (5 значений) — для Kanban dashboard.
// Файл: app/Enums/CaseStatusEnum.php
// ---------------------------------------------------------------
