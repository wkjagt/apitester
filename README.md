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
        name: create_list
        variables:
            client_id: 211691
            list_name: %random_string%
        requests:
            -
                name: post_list_missing_name
                url: /clients/%client_id%/lists
                method: POST
                expects_response:
                    status_code: 400
                    format: json
                    json_values:
                        error_code: 47
            -
                name: post_list
                url: /clients/%client_id%/lists
                method: POST
                data:
                    format: url_encoded
                    values:
                        name: %list_name%
                expects_response:
                    status_code: 200
            -
                name: verify_created_list
                url: /clients/%client_id%/lists/%previous_request.json_data.id%
                method: GET
                expects_response:
                    status_code: 200
                    json_values:
                        id: %previous_request.json_data.id%
            -
                name: delete_created_list
                url: /clients/%client_id%/lists/%previous_request.json_data.id%
                method: DELETE
                expects_response:
                    status_code: 200
    -
        name: update_client
        variables:
            client_id: 211691
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
                        id: (equals:%client_id%)
            -
                name: update_client
                url: /clients/%client_id%
                method: PUT
                data:
                    format: url_encoded
                    values:
                        name: %client_name%

                expects_response:
                    status_code: 200

            -
                name: get_updated_client
                url: /clients/%client_id%
                method: GET
                expects_response:
                    status_code: 200
                    format: json
                    json_values:
                        name: %client_name%
```
