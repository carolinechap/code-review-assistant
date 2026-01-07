<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;

class Analysis
{
    /**
     * @var array<Issue>
     */
    public array $performance;

    /**
     * @var array<Issue>
     */
    public array $security;

    /**
     * @var array<Issue>
     */
    public array $readability;

    /**
     * @var array<Issue>
     */
    #[SerializedName("best_practices")]
    public array $bestPractices;

}
