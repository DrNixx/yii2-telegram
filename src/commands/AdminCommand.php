<?php
namespace onix\telegram\commands;

abstract class AdminCommand extends Command
{
    /**
     * @var bool
     */
    protected $private_only = true;
}
