What is it?
-
This is a simple implementation of an expression tree and several handlers for it. 

What can it do?
-
- You can build query conditions on a relational database (for example, SQL).
- You can check the object for compliance with any expression.
- You can combine both of the above features in the specification and replacing the handler in the repository to get the opportunity for a less painful transition to another database.
- You can expand the existing functionality of the tree and implement special programmatic, logical or other expressions for your own needs. For example, add the ability to conduct mathematical operations or full-fledged object programming.

Usage example.
-
In this example, we will receive an sql query to select retirees and we can check other objects for compliance with the retirement conditions using the same conditions.
```php
$pensionerExpression = new OrExpression(
    new AndExpression(
        new EqualExpression(
            new KeyNode('male'),
            new BooleanNode(true),
        ),
        new GreaterExpression(
            new KeyNode('age'),
            new IntegerNode(60),
        ),
    ),
    new GreaterExpression(
        new KeyNode('age'),
        new FloatNode(55),
    ),
);
...
$sqlHandler = new SQLExpressionHandler();
$sqlConditions = $sqlHandler->handle($pensionerExpression); //(male = true AND age > 60) OR age > 55
$fullSql = sprintf("SELECT name, address, age, male FROM %s WHERE %s", $tableName, $sqlConditions);
...
$isPensionerHandler = new ObjectSatisfyHandler($pensionerExpression);
if ($isPensionerHandler->handle($object)) { 
    print 'Congratulations, you are now a pensioner!';
}
```
Thus, we combined the conditional check and the construction of the conditions for the database, thereby reducing the potential risks during maintenance.

More description coming soon.