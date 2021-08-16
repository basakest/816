<?php

namespace Casbin\Tests\Unit\Util;

use Casbin\Util\Util;
use PHPUnit\Framework\TestCase;

/**
 * UtilTest.
 *
 * @author techlee@qq.com
 */
class UtilTest extends TestCase
{
    public function testEscapeAssertion()
    {
        $this->assertEquals(Util::escapeAssertion('p.attr.value == p.attr'), 'p_attr.value == p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attr.value == p.attr'), 'r_attr.value == p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value || p.attr'), 'r_attp.value || p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value &&p.attr'), 'r_attp.value &&p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value >p.attr'), 'r_attp.value >p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value <p.attr'), 'r_attp.value <p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value +p.attr'), 'r_attp.value +p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value -p.attr'), 'r_attp.value -p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value *p.attr'), 'r_attp.value *p_attr');
        $this->assertEquals(Util::escapeAssertion('r.attp.value /p.attr'), 'r_attp.value /p_attr');
        $this->assertEquals(Util::escapeAssertion('!r.attp.value /p.attr'), '!r_attp.value /p_attr');
        $this->assertEquals(Util::escapeAssertion('g(r.sub, p.sub) == p.attr'), 'g(r_sub, p_sub) == p_attr');
        $this->assertEquals(Util::escapeAssertion('g(r.sub,p.sub) == p.attr'), 'g(r_sub,p_sub) == p_attr');
        $this->assertEquals(Util::escapeAssertion('(r.attp.value || p.attr)p.u'), '(r_attp.value || p_attr)p_u');
    }

    public function testArrayRemoveDuplicates()
    {
        $a = ['green', 'red', 'green', 'blue', 'red'];
        Util::arrayRemoveDuplicates($a);
        $this->assertEquals($a, ['green', 'red', 'blue']);
    }

    public function testContainEval()
    {
        $this->assertEquals(Util::hasEval('eval() && a && b &&c'), true);
        $this->assertEquals(Util::hasEval('eval) && a && b &&c'), false);
        $this->assertEquals(Util::hasEval('eval)( && a && b &&c'), false);
        $this->assertEquals(Util::hasEval('eval() && a && b &&c'), true);
        $this->assertEquals(Util::hasEval('eval(c * (a + b)) && a && b &&c'), true);
        $this->assertEquals(Util::hasEval('xeval() && a && b &&c'), false);
    }

    public function testReplaceEval()
    {
        $this->assertEquals(Util::replaceEval('eval() && a && b && c', 'a'), '(a) && a && b && c');
        $this->assertEquals(Util::replaceEval('eval() && a && b && c', '(a)'), '((a)) && a && b && c');
    }

    public function testGetEvalValue()
    {
        $this->assertEquals(Util::getEvalValue('eval(a) && a && b && c'), ['a']);
        $this->assertEquals(Util::getEvalValue('a && eval(a) && b && c'), ['a']);
        $this->assertEquals(Util::getEvalValue('eval(a) && eval(b) && a && b && c'), ['a', 'b']);
        $this->assertEquals(Util::getEvalValue('a && eval(a) && eval(b) && b && c'), ['a', 'b']);
    }
}
