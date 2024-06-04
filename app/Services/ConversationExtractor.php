<?php

namespace App\Services;

abstract class ConversationExtractor
{
    /**
     * Macro task to execute in the background.
     * @return void
     */
    abstract public function macrotask(): void;

    /**
     * Micro task to run in parallel.
     * @return void
     */
    abstract public function microtask(): void;



}
