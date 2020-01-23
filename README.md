What is it?
-
This is a simple implementation of an expression tree and several handlers for it. 

What is this for?
-
The expression tree was designed specifically for use in conjunction with the repository and specification patterns. In order for the developer to maximally divide the logic into the corresponding layers. So, using the expression tree, you can separate the persistence layer and the business logic layer.

Using the expression tree, your code is one step closer to following the principles of SRP and OCP.

What can it do?
-
- You can build query conditions on a relational database (for example, SQL).
- You can check the object for compliance with any expression.
- You can combine both of the above features in the specification and replacing the handler in the repository to get the opportunity for a less painful transition to another database.
- You can expand the existing functionality of the tree and implement special programmatic, logical or other expressions for your own needs. For example, add the ability to conduct mathematical operations or full-fledged object programming.

Usage example
-
As an example, we implement a simple scheme for obtaining data from a database.

```php
class PostRepository implements PostRepositoryInterface
{
    ...
    public function find(PostRepositorySpecificationInterface $spec)
    {
        $expressionHandler = new SQLExpressionHandler();
        $sqlConditions = $expressionHandler->handle($spec->getExpression());
        $response = $this->db->findOne(
            sprintf("
                    SELECT
                        p.id id,
                        p.title title,
                        (select uid from comment c where c.postid = p.id) commentcount,
                        ...
                    FROM posts p
                    ...
                    WHERE %s
                ",
                $sqlConditions
            )
        );
        ...
        $posts = new PostCollection();
        foreach ($response as $item) {
            $posts->add(
                new Post(
                    $item['id'],
                    $item['title'],
                    $item['commentcount'],
                    ...
                )
            );
        }
    }
    ...
}
```
This is a repository for receiving blog posts, implements the `find` method, which returns, in our case, one post depending on the conditions specified in the specification.

```php
class AuthurPostRepositorySpec implement PostRepositorySpecificationInterface
{
    protected int $authorId;

    public function __construct(int $authorId)
    {
        $this->authorId = $authorId;
    }

    public function getExpression(): ExpressionInterface
    {
        return new EqualExpression(
            new KeyNode('author'),
            new IntegerNode($this->authorId),
        );
    }

    public function isSatisfiedBy(Post $post): bool
    {
        $satisfyHandler = new ObjectSatisfyHandler($this->getExpression());
        return $satisfyHandler->handle($post);
    }
}
```
In this example, we created a specification that we can transfer to the previously created repository and get all the posts of the right user. We can also, using the isSatisfiedBy method, check if other posts are suitable for this condition.

Thus, we successfully separated the data access logic, leaving it in the `PostRepository`, and the application logic, placing it in the` AuthurPostRepositorySpec`. Now we can safely say that our repository does not know anything about what data we need, and business logic is free from the details of access to data.

Expansion options
-
The presented package can be easily extended by writing third-party expressions. To do this, there are several mechanisms that should be considered when creating your own parameters, expressions or handlers.

1) **Types of returned/accepted results.**

   `StKevich\ExpressionTree\ExpressionResult\Types`

   This is a list of basic types that can return/accept expressions/parameters, by combining them you can narrow or extend the boundaries of your expressions.
2) **The number of input parameters.**
   
   `namespace StKevich\ExpressionTree\ExpressionResult\Numbers`
   
   This is the basic interface for interacting with your expression for other expressions and handlers. Parameters, like the final leaves of a tree, stand apart and should always be inherited from `AbstractParameterNode`.
3) **The `exec` method.**

   Must return the values of the execution of your expression, is directly involved in the recursive calculation of the entire tree. Also, a method may well return an error if your expression does not involve participating in a recursive traversal, as it happens, for example with `KeyNode`.
4) **Methods unique to nested structures.**

   For example, the `get` and` is` methods for parameters. You can also create your own basic structures, however, remember that standard handlers cannot work correctly with their methods.


More description coming soon.