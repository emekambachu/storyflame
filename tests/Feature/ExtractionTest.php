<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExtractionTest extends TestCase
{

    /**
     * @group extraction
     * @return void
     */
    public function test_extractor_extracts_parameters()
    {
        $transcripts = [
          "What is your story about?" => "It's a tragic short story about a young woman and her experiences after clone wars and her journey becoming a jedi"
        ];
    }
}
