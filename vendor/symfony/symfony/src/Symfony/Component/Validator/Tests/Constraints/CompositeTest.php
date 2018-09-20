<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Tests\Constraints;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Composite;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Valid;

class ConcreteComposite extends Composite
{
    public $constraints;

    protected function getCompositeOption()
    {
        return 'constraints';
    }

    public function getDefaultOption()
    {
        return 'constraints';
    }
}

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CompositeTest extends TestCase
{
    public function testMergeNestedGroupsIfNoExplicitParentGroup()
    {
        $constraint = new ConcreteComposite(array(
            new NotNull(array('groups' => 'default')),
            new NotBlank(array('groups' => array('default', 'Strict'))),
        ));

        $this->assertEquals(array('default', 'Strict'), $constraint->groups);
        $this->assertEquals(array('default'), $constraint->constraints[0]->groups);
        $this->assertEquals(array('default', 'Strict'), $constraint->constraints[1]->groups);
    }

    public function testSetImplicitNestedGroupsIfExplicitParentGroup()
    {
        $constraint = new ConcreteComposite(array(
            'constraints' => array(
                new NotNull(),
                new NotBlank(),
            ),
            'groups' => array('default', 'Strict'),
        ));

        $this->assertEquals(array('default', 'Strict'), $constraint->groups);
        $this->assertEquals(array('default', 'Strict'), $constraint->constraints[0]->groups);
        $this->assertEquals(array('default', 'Strict'), $constraint->constraints[1]->groups);
    }

    public function testExplicitNestedGroupsMustBeSubsetOfExplicitParentGroups()
    {
        $constraint = new ConcreteComposite(array(
            'constraints' => array(
                new NotNull(array('groups' => 'default')),
                new NotBlank(array('groups' => 'Strict')),
            ),
            'groups' => array('default', 'Strict'),
        ));

        $this->assertEquals(array('default', 'Strict'), $constraint->groups);
        $this->assertEquals(array('default'), $constraint->constraints[0]->groups);
        $this->assertEquals(array('Strict'), $constraint->constraints[1]->groups);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testFailIfExplicitNestedGroupsNotSubsetOfExplicitParentGroups()
    {
        new ConcreteComposite(array(
            'constraints' => array(
                new NotNull(array('groups' => array('default', 'Foobar'))),
            ),
            'groups' => array('default', 'Strict'),
        ));
    }

    public function testImplicitGroupNamesAreForwarded()
    {
        $constraint = new ConcreteComposite(array(
            new NotNull(array('groups' => 'default')),
            new NotBlank(array('groups' => 'Strict')),
        ));

        $constraint->addImplicitGroupName('ImplicitGroup');

        $this->assertEquals(array('default', 'Strict', 'ImplicitGroup'), $constraint->groups);
        $this->assertEquals(array('default', 'ImplicitGroup'), $constraint->constraints[0]->groups);
        $this->assertEquals(array('Strict'), $constraint->constraints[1]->groups);
    }

    public function testSingleConstraintsAccepted()
    {
        $nestedConstraint = new NotNull();
        $constraint = new ConcreteComposite($nestedConstraint);

        $this->assertEquals(array($nestedConstraint), $constraint->constraints);
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testFailIfNoConstraint()
    {
        new ConcreteComposite(array(
            new NotNull(array('groups' => 'default')),
            'NotBlank',
        ));
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testFailIfNoConstraintObject()
    {
        new ConcreteComposite(array(
            new NotNull(array('groups' => 'default')),
            new \ArrayObject(),
        ));
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\ConstraintDefinitionException
     */
    public function testValidCantBeNested()
    {
        new ConcreteComposite(array(
            new Valid(),
        ));
    }
}
