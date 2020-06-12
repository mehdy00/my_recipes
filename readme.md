# Short Symfony API for technical test (KLH Competence)

# Features :

  - Get all recipes
  - Create one recipe
  - Update one recipe
  - Delete one recipe

Dont forget to migrate with doctrine 
```sh
Get all : GET Method

http://127.0.0.1:8000/recipes

Create one : POST Method
request body example :
http://127.0.0.1:8000/recipe
{
	"title": "My favorite recipe",
    "subtitle": "this is very delicious",
    "ingredient": "Tomato, pasta, cheese"
}

Update one : PUT Method
request body example :
http://127.0.0.1:8000/recipe/edit/{id}
{
	"title": "My new title",
    "subtitle": "this is very delicious",
    "ingredient": "Tomato, pasta, cheese and yogurt"
}

Delete one : POST Method
http://127.0.0.1:8000/recipe/delete/{id}