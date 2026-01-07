<?php

namespace App\Dto;

use Symfony\Component\Serializer\Attribute\SerializedName;

class CodeAnalysisResponse
{
    /**
     * @var Analysis
     */
    public Analysis $analysis;

    /**
     * @var ImprovedCode
     */
    #[SerializedName('improved_code')]
    public ImprovedCode $improvedCode;
}
