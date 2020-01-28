<?php

namespace Yiisoft\Yii\Gii\Console;

/**
 * This is the command line version of Gii - a code generator.
 *
 * You can use this command to generate models, controllers, etc. For example,
 * to generate an ActiveRecord model based on a DB table, you can run:
 *
 * ```
 * $ ./yii gii/model --tableName=city --modelClass=City
 * ```
 */
class ControllerCommand extends BaseGenerateCommand
{
    protected static $defaultName = 'gii/controller';

    /**
     * {@inheritdoc}
     */
    public function getActionHelpSummary($action)
    {
        if ($action instanceof InlineAction) {
            return parent::getActionHelpSummary($action);
        }

        /** @var $action BaseGenerateCommand */
        return $action->generator->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getActionHelp($action)
    {
        if ($action instanceof InlineAction) {
            return parent::getActionHelp($action);
        }

        /** @var $action BaseGenerateCommand */
        $description = $action->generator->getDescription();
        return wordwrap(preg_replace('/\s+/', ' ', $description));
    }

    /**
     * {@inheritdoc}
     */
    public function getActionArgsHelp($action)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getActionOptionsHelp($action)
    {
        if ($action instanceof InlineAction) {
            return parent::getActionOptionsHelp($action);
        }
        /** @var $action BaseGenerateCommand */
        $attributes = $action->generator->attributes;
        unset($attributes['templates']);
        $hints = $action->generator->hints();

        $options = parent::getActionOptionsHelp($action);
        foreach ($attributes as $name => $value) {
            $type = gettype($value);
            $options[$name] = [
                'type' => $type === 'NULL' ? 'string' : $type,
                'required' => $value === null && $action->generator->isAttributeRequired($name),
                'default' => $value,
                'comment' => isset($hints[$name]) ? $this->formatHint($hints[$name]) : '',
            ];
        }

        return $options;
    }

    protected function formatHint($hint)
    {
        $hint = preg_replace('%<code>(.*?)</code>%', '\1', $hint);
        $hint = preg_replace('/\s+/', ' ', $hint);
        return wordwrap($hint);
    }
}
