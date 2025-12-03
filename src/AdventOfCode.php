<?php

declare(strict_types=1);

namespace AdventOfCode;

final class AdventOfCode
{
    private array $tasks = [];

    /**
     * @param class-string<Task>[] $tasks
     */
    public function tasks(int $year, array $tasks): self
    {
        $this->tasks[$year] = array_filter(
            $tasks,
            static fn (string $task) => is_a($task, Task::class, true),
        );

        return $this;
    }

    public function run(int $year, string $file): void
    {
        foreach ($this->tasks[$year] ?? [] as $task) {
            /** @var class-string<Task> $task */
            $task::run(File::path($year, $task::day(), $file));
        }
    }
}
