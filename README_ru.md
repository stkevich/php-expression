Что это?
-
Это простая реализация дерева выражения и нескольких обработчиков для нее. 

Что это может?
-
- Можно построить условия запроса к реляционной базе данных (например SQL).
- Можно проводить проверку объекта на соответствие какому либо выражению.
- Можно объединить обе выше описанные возможности в спецификации и подменяя в репозитории обработчик получить возможность менее болезненного перехода на другую бд.
- Можно расширить существующий функционал дерева и реализовать особые программные, логические или иные выражения для собственных нужд. Например, добавить возможность проводить математические операции или полноценное объектное программирование.

Пример использования.
-
В этом примере мы получим sql запрос для выборки пенсионеров и сможем проверить другие объекты на соответствие условиям выхода на пенсию используя те же самые условия.

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
Таким образом мы объединили проверку по условию и построение условия для бд, сократив тем самым потенциальные риски при сопровождении.

More description coming soon.