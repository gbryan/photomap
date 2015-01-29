<?php

use Jenssegers\Mongodb\Model as Eloquent;
use PhotoMap\Observers\ValidationObserver;

class ValidatingModel extends Eloquent
{
    /**
     * Validation error messages
     * @var \Illuminate\Support\MessageBag
     */
    public $errors;

    /**
     * Validator instance that we can pass to withErrors() using Redirect.
     * @var Illuminate\Support\Facades\Validator
     */
    public $validator;

    /**
     * Whether to automatically validate attribute values when saving
     * @var boolean
     */
    protected static $validateOnSave = true;

    public static function boot()
    {
        parent::boot();

        if (static::$validateOnSave)
        {
            static::observe(new ValidationObserver);
        }
    }
    
    /**
     * Validate the model's attributes, given a set of rules.
     * @param  array $values Model values, such as Input::all()
     * @return bool
     */
    public function validate($values = array(), $rules = array(), $messages = array())
    {
        $values = (!empty($values)) ? $values : $this->attributes;
        $rules = (!empty($rules)) ? $rules : $this->getRules();
        $messages = (!empty($messages)) ? $messages : $this->getMessages();

        $validation = Validator::make($values, $rules, $messages);

        if ($validation->passes()) return true;

        $this->errors = $validation->messages();
        $this->validator = $validation;

        return false;
    }

    /**
     * Get the validation rules for the given action.
     * @param  string   $action
     * @return array
     */
    public function getRules($action = null)
    {
        return $this->getValidationParameters('validationRules', $action);
    }

    /**
     * Get the validation messages for the given action.
     * @param  Model    $model
     * @param  string   $action
     * @return array
     */
    public function getMessages($action = null)
    {
        return $this->getValidationParameters('validationMessages', $action);
    }

    /**
     * Get the validation parameters of the given name (such as validationRules or validationMessages) from the current model for the given action.
     * @param  string   $parameterName
     * @param  string   $action
     * @return array
     */
    protected function getValidationParameters($parameterName, $action)
    {
        $propertyName = $parameterName . $action;

        if (property_exists($this, $propertyName) && !empty($this->$propertyName))
        {
            return $this->$propertyName;
        }

        if (property_exists($this, $parameterName) && !empty($this->$parameterName))
        {
            return $this->$parameterName;
        }

        return [];
    }
}
