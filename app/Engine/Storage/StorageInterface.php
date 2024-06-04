<?php

namespace App\Engine\Storage;

use App\Models\Achievement;
use App\Models\ChatMessage;

interface StorageInterface
{

    /**
     * Get the user id of the conversation
     * @return int
     */
    public function getUserId(): string;

    /**
     * Get the data of the conversation
     * @return array
     */
    public function getData(): array;

    /**
     * Save the data of the conversation
     * @param array $data
     * @return void
     */
    public function setData(array $data): void;

    /**
     * Get the history of the conversation
     * @return ChatMessage[]
     */
    public function getHistory(): array;

    /**
     * Push a message to storage
     * @param ChatMessage $message
     * @return void
     */
    public function push(ChatMessage $message): void;

    /**
     * Get the identifier of the conversation
     * usually the type of storage followed by an underscore and a unique id
     * not an actual id from the database
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Set the identifier of the conversation,
     * this method should also add a change_identifier object to the queue
     * @param string $uid
     * @return void
     */
    public function setIdentifier(string $uid): void;

    /**
     * Get all data extracted from the conversation
     * @return array
     */
    public function getExtractedData(): array;

    /**
     * Save the extracted data
     * @param array $extracted
     * @return void
     */
    public function saveExtractedData(array $extracted): void;

    /**
     * Get the branches of the conversation
     * @return array
     */
    public function getBranches(): array;

    /**
     * Get the current unfinished branch
     * @return bool|array
     */
    public function getCurrentBranch(): bool|array;

    /**
     * Change the topic of the conversation
     * @param Achievement $topic
     * @param string|null $branch_name
     * @return void
     */
    public function setCurrentBranch(Achievement $topic, string $branch_name = null): void;

    /**
     * Mark current branch as finished
     * @return void
     */
    public function markBranchAsFinished(): void;

    /**
     * Push an object to the end of the queue
     * @param array $array The object to push
     * @return int The new length of the queue
     */
    public function pushQueue(array $array): int;

    /**
     * Pop the first element of the queue
     * @return array|bool The first element of the queue or false if the queue is empty
     */
    public function popQueue(): array|bool;
}
