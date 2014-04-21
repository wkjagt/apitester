apitester
=========

Testing tool for web services.

Run with: `php tester.php apitester:runall tests.yml`

Example tests file `tests.yml`

```yml
globals:
    request:
        base_url: http://your.api.com
        defaults:
            headers:
                apikey: your-api-key


sequences:
    -
        name: update_client
        variables:
            client_id: 1234
            client_name: %random_string%
        requests:
            -
                name: get_client
                url: /clients/%client_id%
                method: GET
                expects_response:
                    status_code: 200
                    format: json
                    json_values:
                        -
                            key: id
                            value: %client_id%
            -
                name: update_client
                url: /clients/%client_id%
                method: PUT
                data:
                    format: url_encoded
                    values:
                        name: %client_name%

                expects_response:
                    status_code: 201

            -
                name: get_updated_client
                url: /clients/%client_id%
                method: GET
                expects_response:
                    status_code: 200
                    format: json
                    json_values:
                        -
                            key: name
                            value: %client_name%
```
