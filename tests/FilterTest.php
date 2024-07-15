<?php

namespace CP\Filter\tests;

use PHPUnit\Framework\TestCase;
use CP\Filter\Filter;
use CP\Filter\Tokens as T;

class FilterTest extends TestCase
{
    /** @var Filter $filter */
    protected $filter;

    protected $join_id;
    protected $field_id;
    protected $history_table;
    protected $sensor_table;

    public function setUp(): void
    {
        $this->filter = new Filter();
    }

    public function testCmp()
    {
        $expect = new T\EqExpr(
            new T\IntVal( '1' ),
            new T\IntVal( '2' ),
        );

        $ast = $this->filter->getAst('eq(1, 2)');
        $ast2 = $this->filter->getAst('eq(1,2)');

        $this->assertEquals( $expect, $ast, 'Not valid eq ast' );
        $this->assertEquals( $expect, $ast2, 'Not valid eq ast (whitespace)' );
    }

    public function testCmpFld()
    {
        $expect = new T\EqExpr(
            new T\FldVal('Id'),
            new T\StrVal( '"str"' ), // constructor will remove ""
        );

        $ast = $this->filter->getAst('eq(Id, "str")');

        $this->assertEquals( $expect, $ast, 'Not valid field with string ast' );
    }

    public function testEscapeStr()
    {
        $expect = new T\NotExpr( new T\StrVal('"a\\"b\\"c"') );
        $ast = $this->filter->getAst('not("a\\"b\\"c")');

        $this->assertEquals( $expect, $ast, 'Not valid escape str in ast' );

        $expect = new T\NotExpr( new T\StrVal("'a\\'b\\'c'") );
        $ast = $this->filter->getAst("not('a\\'b\\'c')");

        $this->assertEquals( $expect, $ast, 'Not valid escape str in ast' );

        $expect = new T\NotExpr( new T\StrVal("'a\\c'") );
        $ast = $this->filter->getAst("not('a\\c')");

        $this->assertEquals( $expect, $ast, 'Not valid escape str in ast' );
    }

    public function eqApplyTest()
    {
        $ast = new T\EqExpr(
            new T\FldVal( 'ApplyFld' ),
            new T\StrVal( '"value"' ),
            '='
        );

        $this->assertTrue( $ast->apply(['ApplyFld' => 'value']), 'Not valid apply with str and fld' );
        $this->assertFalse( $ast->apply(['ApplyFld' => 'value2']), 'Not valid apply with str and fld' );
    }

    public function testAndExpr()
    {
        $expect = new T\AndExpr(
            new T\IntVal( '1' ),
            new T\IntVal( '0' )
        );

        $ast = $this->filter->getAst('and(1, 0)');
        $this->assertEquals( $expect, $ast, 'not valid and' );
    }

    public function testOrExpr()
    {
        $expect = new T\OrExpr(
            new T\IntVal( '1' ),
            new T\IntVal( '0' )
        );

        $ast = $this->filter->getAst('or(1, 0)');
        $this->assertEquals( $expect, $ast, 'not valid or' );
    }

    public function testInExpr()
    {
        $expect = new T\InExpr(
            new T\FldVal( 'Skqoda_Karoq' ),
            new T\StrVal( '"%Karoq"' )
        );

        $ast = $this->filter->getAst('in(Skqoda_Karoq, "%Karoq")');
        $this->assertEquals( $expect, $ast, 'Not valid escape str in ast' );


        $expect = new T\InExpr(
            new T\FldVal( "Audi_A6" ),
            new T\StrVal( "'Audi%'" )
        );

        $ast = $this->filter->getAst("in(Audi_A6, 'Audi%')");
        $this->assertEquals( $expect, $ast, 'Not valid escape str in ast' );

        $expect = new T\InExpr(
            new T\FldVal( 'BMW' ),
            new T\StrVal( '"BMW"' )
        );

        $ast = $this->filter->getAst('in(BMW, "BMW")');
        $this->assertEquals( $expect, $ast, 'Not valid escape str in ast' );

    }

}
