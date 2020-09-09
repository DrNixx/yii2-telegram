<?php
namespace onix\telegram\commands\system;

use onix\telegram\commands\CallbackQueryHandler;
use onix\telegram\commands\SystemCommand;
use onix\telegram\entities\ServerResponse;

/**
 * Callback query command
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var CallbackQueryHandler[]
     */
    protected $callbacks = [];

    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * @inheritdoc
     */
    protected $show_in_help = false;

    /**
     * Command execute method
     *
     * @return ServerResponse
     */
    public function execute()
    {
        $answer = null;
        $callback_query = $this->callbackQuery;

        // Call all registered callbacks.
        foreach ($this->callbacks as $name => $callback) {
            $answer = $callback->callbackHandler($callback_query);
            if ($answer instanceof ServerResponse) {
                return $answer;
            }
        }

        return $callback_query->answer();
    }

    /**
     * Add a new callback handler for callback queries.
     *
     * @param string $name
     * @param CallbackQueryHandler $callback
     */
    public function addCallbackHandler(string $name, CallbackQueryHandler $callback)
    {
        $this->callbacks[$name] = $callback;
    }
}
