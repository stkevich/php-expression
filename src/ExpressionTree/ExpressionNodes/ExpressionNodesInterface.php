<?php

declare(strict_types=1);

namespace StKevich\ExpressionTree\ExpressionNodes;

use StKevich\ExpressionTree\ExpressionInterface;

interface ExpressionNodesInterface extends ExpressionInterface
{
    /**
     * Must return a standard view that can identify this expression node.
     * The $viewType parameter specifies the type of the standard representation of the expression (and/&&/etc.).
     * @param string $viewType
     * @return string
     */
    public function standardGlue(string $viewType = null): string;

}
