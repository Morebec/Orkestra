<?php


namespace Morebec\Orkestra\Workflow;

use InvalidArgumentException;
use Morebec\Collections\HashMap;

/**
 * Represents the state of a given workflow execution
 */
final class WorkflowState
{
    /** @var bool Indicates if this workflow was completed or not */
    private $completed;

    /** @var string UniqueId for this WorkflowState */
    private $id;

    /** @var string Id of the Workflow */
    private $workflowId;

    /** @var HashMap<string, mixed> */
    private $data;

    public function __construct(string $id, string $workflowId)
    {
        $this->id = $id;
        $this->workflowId = $workflowId;
        $this->completed = false;
        $this->data = new HashMap();
    }

    /**
     * Constructs a state object from an array representation
     * @param array<string, mixed> $stateArray
     * @return static
     */
    public static function fromArray(array $stateArray): self
    {
        $state = new static($stateArray['id'], $stateArray['workflow_id']);
        $state->data = new HashMap($stateArray['data']);
        $state->completed = $stateArray['completed'];
        return $state;
    }

    /**
     * Returns the unique id of this state
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Returns the id of the workflow this state applies to
     * @return string
     */
    public function getWorkflowId(): string
    {
        return $this->workflowId;
    }

    /**
     * Sets or override a value for a given key. Please refrain to using a primitive values
     * so this state can be serialised for persistence. Otherwise, you will need to implement your
     * own WorkflowStateRepository
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->data->put($key, $value);
    }

    /**
     * Returns the value of a given key or throws an exception
     * if the key was not previously set.
     * @param string $key
     * @return mixed|null
     * @throws InvalidArgumentException
     */
    public function get(string $key)
    {
        if (!$this->containsKey($key)) {
            throw new InvalidArgumentException(
                "Key $key not found for state {$this->id} for workflow {$this->workflowId}"
            );
        }

        return $this->data->get($key);
    }

    /**
     * Indicates if a key exists on this state
     * @param string $key
     * @return bool
     */
    public function containsKey(string $key): bool
    {
        return $this->data->containsKey($key);
    }

    /**
     * Returns a copy of this state's data
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = $this->data->toArray();

        return [
            'id' => $this->id,
            'workflow_id' => $this->workflowId,
            'completed' => $this->completed,
            'data' => $data
        ];
    }

    /**
     * Marks this workflow as completed
     */
    public function markCompleted(): void
    {
        $this->completed = true;
    }

    /**
     * Indicates if the workflow was completed
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }
}
