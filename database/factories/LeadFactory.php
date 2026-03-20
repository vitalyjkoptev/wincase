<?php

namespace Database\Factories;

use App\Enums\LeadSourceEnum;
use App\Enums\LeadStatusEnum;
use App\Enums\PriorityEnum;
use App\Enums\ServiceTypeEnum;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lead>
 */
class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        $source = fake()->randomElement(LeadSourceEnum::cases());
        $status = fake()->randomElement(LeadStatusEnum::cases());
        $languages = ['pl', 'en', 'ru', 'ua', 'hi', 'tl', 'es', 'tr'];

        $domains = [
            'wincase.pro',
            'wincase-legalization.com',
            'wincase-job.com',
            'wincase.org',
        ];

        return [
            'name' => fake()->name(),
            'phone' => fake()->e164PhoneNumber(),
            'email' => fake()->optional(0.7)->safeEmail(),
            'service_type' => fake()->randomElement(ServiceTypeEnum::cases()),
            'message' => fake()->optional(0.5)->sentence(10),
            'source' => $source,
            'utm_source' => $source->isPaid() ? fake()->randomElement(['google', 'facebook', 'tiktok', 'pinterest', 'youtube']) : null,
            'utm_medium' => $source->isPaid() ? fake()->randomElement(['cpc', 'cpm', 'display']) : null,
            'utm_campaign' => $source->isPaid() ? fake()->slug(3) : null,
            'utm_term' => fake()->optional(0.2)->words(3, true),
            'utm_content' => fake()->optional(0.2)->slug(2),
            'gclid' => $source === LeadSourceEnum::GOOGLE_ADS ? fake()->uuid() : null,
            'fbclid' => $source === LeadSourceEnum::FACEBOOK_ADS ? fake()->uuid() : null,
            'ttclid' => $source === LeadSourceEnum::TIKTOK_ADS ? fake()->uuid() : null,
            'landing_page' => '/' . fake()->slug(2),
            'language' => fake()->randomElement($languages),
            'device' => fake()->randomElement(['desktop', 'mobile', 'tablet']),
            'ip_address' => fake()->ipv4(),
            'country' => fake()->randomElement(['PL', 'UA', 'IN', 'PH', 'TR', 'ES', 'RU', 'DE']),
            'city' => fake()->city(),
            'status' => $status,
            'assigned_to' => null,
            'priority' => fake()->randomElement(PriorityEnum::cases()),
            'notes' => fake()->optional(0.3)->sentence(),
            'first_contact_at' => $status !== LeadStatusEnum::NEW ? fake()->dateTimeBetween('-30 days', 'now') : null,
            'consultation_at' => in_array($status, [LeadStatusEnum::CONSULTATION, LeadStatusEnum::CONTRACT, LeadStatusEnum::PAID])
                ? fake()->dateTimeBetween('-20 days', 'now') : null,
            'converted_at' => $status === LeadStatusEnum::PAID ? fake()->dateTimeBetween('-10 days', 'now') : null,
            'gdpr_consent' => true,
            'gdpr_consent_at' => now(),
        ];
    }

    // =====================================================
    // STATES
    // =====================================================

    public function newLead(): static
    {
        return $this->state(fn () => [
            'status' => LeadStatusEnum::NEW,
            'first_contact_at' => null,
            'consultation_at' => null,
            'converted_at' => null,
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn () => [
            'status' => LeadStatusEnum::PAID,
            'first_contact_at' => fake()->dateTimeBetween('-30 days', '-20 days'),
            'consultation_at' => fake()->dateTimeBetween('-20 days', '-10 days'),
            'converted_at' => fake()->dateTimeBetween('-10 days', 'now'),
        ]);
    }

    public function fromGoogleAds(): static
    {
        return $this->state(fn () => [
            'source' => LeadSourceEnum::GOOGLE_ADS,
            'gclid' => fake()->uuid(),
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
        ]);
    }

    public function fromFacebook(): static
    {
        return $this->state(fn () => [
            'source' => LeadSourceEnum::FACEBOOK_ADS,
            'fbclid' => fake()->uuid(),
            'utm_source' => 'facebook',
            'utm_medium' => 'cpc',
        ]);
    }

    public function urgent(): static
    {
        return $this->state(fn () => [
            'priority' => PriorityEnum::URGENT,
        ]);
    }

    public function ukrainian(): static
    {
        return $this->state(fn () => [
            'language' => 'ua',
            'country' => 'UA',
            'service_type' => ServiceTypeEnum::TEMPORARY_PROTECTION,
        ]);
    }
}

// ---------------------------------------------------------------
// Аннотация (RU):
// Фабрика LeadFactory — генерация тестовых лидов для development/testing.
// Автоматически заполняет UTM и click IDs в зависимости от source.
// 8 языков, 8 стран, 7 типов услуг, все статусы воронки.
// States: newLead(), paid(), fromGoogleAds(), fromFacebook(),
// urgent(), ukrainian() — для специфических тестовых сценариев.
// Файл: database/factories/LeadFactory.php
// ---------------------------------------------------------------
