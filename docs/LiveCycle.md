# Index
```
$controller->index()
    $rep = $controller->getModel(Request)
    $rep->getCollection(Request)
        $rep->getModels(Request)
            $rep->getQuery()
                $rep->afterGetQuery()
            $rep->applyScopes(Request, Query)
                $rep->getScopes()
                $rep->setScope(Query, Scope, RequestParam)
            $rep->applyConstScopes(Query)
            $rep->getPaginate(Query, Request)
        $rep->afterGetModels(Resource)
```

# Show
```
$controller->show()
    $rep = $controller->getModel(Request)
    $rep->getResource(Id)
        $rep->getObjectById(Id)
            $rep->formatId(Id)
                $rep->findById(Id)
                $rep->afterFind(Object)
            $rep->afterCreate(Object)
        $rep->afterGetModels(Resource)
```

# Store
```
$controller->store()
    $rep = $controller->getModel(Request)
    $rep->store()
        $rep->getObject(Request)
            $rep->getObjectById(Id)
                $rep->formatId(Id)
                    $rep->findById(Id)
                    $rep->afterFind(Object)
                $rep->afterCreate(Object)

        $rep->getData(Request)
        $rep->storeObject(Object, DataArray, Request)
            $rep->beforeSave(Object, DataArray)
            $rep->afterSave(Object, Request)
```

# Destroy
```
$controller->destroy()
    $rep = $controller->getModel(Request)
    $rep->destroy(Id)
        $rep->getObjectById(Id)
            $rep->formatId(Id)
                $rep->findById(Id)
                $rep->afterFind(Object)
            $rep->afterCreate(Object)
        $rep->beforeDestroy(Object)
        $rep->afterDestroy(Id)
```
