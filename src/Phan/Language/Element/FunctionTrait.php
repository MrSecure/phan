<?php declare(strict_types=1);
namespace Phan\Language\Element;

use \Phan\CodeBase;
use \Phan\Language\Context;
use \Phan\Language\FQSEN\FullyQualifiedFunctionName;
use \Phan\Language\Type\NullType;
use \Phan\Language\UnionType;

trait FunctionTrait {

    /**
     * @return int
     */
    abstract public function getFlags() : int;

    /**
     * @param int $flags
     *
     * @return void
     */
    abstract public function setFlags(int $flags);


    /**
     * @var int
     * The number of required parameters for the method
     */
    private $number_of_required_parameters = 0;

    /**
     * @var int
     * The number of optional parameters for the method
     */
    private $number_of_optional_parameters = 0;

    /**
     * @var Parameter[]
     * The list of parameters for this method
     */
    private $parameter_list = [];

    /**
     * @return int
     * The number of optional parameters on this method
     */
    public function getNumberOfOptionalParameters() : int {
        return $this->number_of_optional_parameters;
    }

    /**
     * The number of optional parameters
     *
     * @return void
     */
    public function setNumberOfOptionalParameters(int $number) {
        $this->number_of_optional_parameters = $number;
    }

    /**
     * @return int
     * The maximum number of parameters to this method
     */
    public function getNumberOfParameters() : int {
        return (
            $this->getNumberOfRequiredParameters()
            + $this->getNumberOfOptionalParameters()
        );
    }

    /**
     * @return int
     * The number of required parameters on this method
     */
    public function getNumberOfRequiredParameters() : int {
        return $this->number_of_required_parameters;
    }

    /**
     *
     * The number of required parameters
     *
     * @return void
     */
    public function setNumberOfRequiredParameters(int $number) {
        $this->number_of_required_parameters = $number;
    }

    /**
     * @return bool
     * True if this method had no return type defined when it
     * was defined (either in the signature itself or in the
     * docblock).
     */
    public function isReturnTypeUndefined() : bool
    {
        return Flags::bitVectorHasState(
            $this->getFlags(),
            Flags::IS_RETURN_TYPE_UNDEFINED
        );
    }

    /**
     * @param bool $is_return_type_undefined
     * True if this method had no return type defined when it
     * was defined (either in the signature itself or in the
     * docblock).
     *
     * @return void
     */
    public function setIsReturnTypeUndefined(
        bool $is_return_type_undefined
    ) {
        $this->setFlags(Flags::bitVectorWithState(
            $this->getFlags(),
            Flags::IS_RETURN_TYPE_UNDEFINED,
            $is_return_type_undefined
        ));
    }

    /**
     * @return bool
     * True if this method returns a value
     */
    public function getHasReturn() : bool
    {
        return Flags::bitVectorHasState(
            $this->getFlags(),
            Flags::HAS_RETURN
        );
    }

    /**
     * @param bool $has_return
     * Set to true to mark this method as having a
     * return value
     *
     * @return void
     */
    public function setHasReturn(bool $has_return)
    {
        $this->setFlags(Flags::bitVectorWithState(
            $this->getFlags(),
            Flags::HAS_RETURN,
            $has_return
        ));
    }

    /**
     * @return Parameter[]
     * A list of parameters on the method
     */
    public function getParameterList() {
        return $this->parameter_list;
    }

    /**
     * @param Parameter[] $parameter_list
     * A list of parameters to set on this method
     *
     * @return void
     */
    public function setParameterList(array $parameter_list) {
        $this->parameter_list = $parameter_list;
    }

    /**
     * @param Parameter $parameter
     * A parameter to append to the parameter list
     *
     * @return void
     */
    public function appendParameter(Parameter $parameter) {
        $this->parameter_list[] = $parameter;
    }

}
