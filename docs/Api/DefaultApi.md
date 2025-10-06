# Aternos\SpigotApi\DefaultApi

All URIs are relative to https://api.spigotmc.org/simple/0.2, except if the operation defines another base path.

| Method | HTTP request | Description |
| ------------- | ------------- | ------------- |
| [**findAuthor()**](DefaultApi.md#findAuthor) | **GET** /index.php?action&#x3D;findAuthor | Obtain an author/user by their name |
| [**getAuthor()**](DefaultApi.md#getAuthor) | **GET** /index.php?action&#x3D;getAuthor | Obtain an author/user by their id |
| [**getResource()**](DefaultApi.md#getResource) | **GET** /index.php?action&#x3D;getResource | Obtain a resource |
| [**getResourceUpdate()**](DefaultApi.md#getResourceUpdate) | **GET** /index.php?action&#x3D;getResourceUpdate | Obtain a specific update to a resource |
| [**getResourceUpdates()**](DefaultApi.md#getResourceUpdates) | **GET** /index.php?action&#x3D;getResourceUpdates | Obtain all the updates to a resource |
| [**getResourcesByAuthor()**](DefaultApi.md#getResourcesByAuthor) | **GET** /index.php?action&#x3D;getResourcesByAuthor | Obtain a list of all resources by a specific author/user |
| [**listResourceCategories()**](DefaultApi.md#listResourceCategories) | **GET** /index.php?action&#x3D;listResourceCategories | Obtain a list of all resource categories |
| [**listResources()**](DefaultApi.md#listResources) | **GET** /index.php?action&#x3D;listResources | Obtain a list of all resources |


## `findAuthor()`

```php
findAuthor($name): \Aternos\SpigotApi\Model\Author
```

Obtain an author/user by their name

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$name = 'name_example'; // string | The author username

try {
    $result = $apiInstance->findAuthor($name);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->findAuthor: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **name** | **string**| The author username | |

### Return type

[**\Aternos\SpigotApi\Model\Author**](../Model/Author.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getAuthor()`

```php
getAuthor($id): \Aternos\SpigotApi\Model\Author
```

Obtain an author/user by their id

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 56; // int | The author ID

try {
    $result = $apiInstance->getAuthor($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getAuthor: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **int**| The author ID | |

### Return type

[**\Aternos\SpigotApi\Model\Author**](../Model/Author.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getResource()`

```php
getResource($id): \Aternos\SpigotApi\Model\Resource
```

Obtain a resource

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 56; // int | The resource ID

try {
    $result = $apiInstance->getResource($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getResource: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **int**| The resource ID | |

### Return type

[**\Aternos\SpigotApi\Model\Resource**](../Model/Resource.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getResourceUpdate()`

```php
getResourceUpdate($id): \Aternos\SpigotApi\Model\ResourceUpdate
```

Obtain a specific update to a resource

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 56; // int | The resource update ID

try {
    $result = $apiInstance->getResourceUpdate($id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getResourceUpdate: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **int**| The resource update ID | |

### Return type

[**\Aternos\SpigotApi\Model\ResourceUpdate**](../Model/ResourceUpdate.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getResourceUpdates()`

```php
getResourceUpdates($id, $page): \Aternos\SpigotApi\Model\ResourceUpdate[]
```

Obtain all the updates to a resource

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 56; // int | The resource ID
$page = 56; // int | The page of results to get

try {
    $result = $apiInstance->getResourceUpdates($id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getResourceUpdates: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **int**| The resource ID | |
| **page** | **int**| The page of results to get | [optional] |

### Return type

[**\Aternos\SpigotApi\Model\ResourceUpdate[]**](../Model/ResourceUpdate.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `getResourcesByAuthor()`

```php
getResourcesByAuthor($id, $page): \Aternos\SpigotApi\Model\Resource[]
```

Obtain a list of all resources by a specific author/user

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$id = 56; // int | The author/user id
$page = 56; // int | The page of results to get

try {
    $result = $apiInstance->getResourcesByAuthor($id, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->getResourcesByAuthor: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **id** | **int**| The author/user id | |
| **page** | **int**| The page of results to get | [optional] |

### Return type

[**\Aternos\SpigotApi\Model\Resource[]**](../Model/Resource.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listResourceCategories()`

```php
listResourceCategories(): \Aternos\SpigotApi\Model\ResourceCategory[]
```

Obtain a list of all resource categories

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);

try {
    $result = $apiInstance->listResourceCategories();
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->listResourceCategories: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

This endpoint does not need any parameter.

### Return type

[**\Aternos\SpigotApi\Model\ResourceCategory[]**](../Model/ResourceCategory.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)

## `listResources()`

```php
listResources($category, $page): \Aternos\SpigotApi\Model\Resource[]
```

Obtain a list of all resources

### Example

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');



$apiInstance = new Aternos\SpigotApi\Api\DefaultApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client()
);
$category = 56; // int | The category ID from which to draw resources
$page = 56; // int | The page of results to get

try {
    $result = $apiInstance->listResources($category, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling DefaultApi->listResources: ', $e->getMessage(), PHP_EOL;
}
```

### Parameters

| Name | Type | Description  | Notes |
| ------------- | ------------- | ------------- | ------------- |
| **category** | **int**| The category ID from which to draw resources | [optional] |
| **page** | **int**| The page of results to get | [optional] |

### Return type

[**\Aternos\SpigotApi\Model\Resource[]**](../Model/Resource.md)

### Authorization

No authorization required

### HTTP request headers

- **Content-Type**: Not defined
- **Accept**: `application/json`

[[Back to top]](#) [[Back to API list]](../../README.md#endpoints)
[[Back to Model list]](../../README.md#models)
[[Back to README]](../../README.md)
