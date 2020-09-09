<?php
namespace onix\telegram\commands;

abstract class UserCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected $category = 'User';

    /**
     * Make sure this command only executes for linked users.
     *
     * @var bool
     */
    protected $linked_only = false;

    /**
     * If this command is intended for linked users only.
     *
     * @return bool
     */
    public function isLinkedOnly()
    {
        return $this->linked_only;
    }
}
