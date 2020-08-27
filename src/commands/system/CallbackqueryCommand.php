<?php
namespace onix\telegram\commands\system;

use onix\telegram\commands\SystemCommand;
use onix\telegram\entities\ServerResponse;

/**
 * Callback query command
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var callable[]
     */
    protected static $callbacks = [];

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
        //$callback_query = $this->getCallbackQuery();
        //$user_id        = $callback_query->getFrom()->getId();
        //$query_id       = $callback_query->getId();
        //$query_data     = $callback_query->getData();

        $answer = null;
        $callback_query = $this->callbackQuery;

        // Call all registered callbacks.
        foreach (self::$callbacks as $callback) {
            $answer = $callback($callback_query);
        }

        return ($answer instanceof ServerResponse) ? $answer : $callback_query->answer();
    }

    /**
     * Add a new callback handler for callback queries.
     *
     * @param $callback
     */
    public static function addCallbackHandler($callback)
    {
        self::$callbacks[] = $callback;
    }
}
