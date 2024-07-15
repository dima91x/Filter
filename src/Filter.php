<?php

namespace CP\Filter;

use Carbon\Carbon;
use CP\Filter\Exceptions as Ex;
use CP\Filter\Tokens;
use PHPUnit\Runner\Exception;

final class Filter
{
    public function getAst(string $formula): Tokens\ASTNode
    {
        $parser = new Parser();
        try{
            $ast = $parser->parse($formula);
        } catch (SyntaxException $e){
            throw new Ex\SyntaxException($e->getMessage());
        }

        return $ast;
    }
}
