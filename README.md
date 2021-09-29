# Google Sheets

Пример конфигурационного файла

```yaml
parameters:
  google.client.config:
    access_type: offline
    credentials: '%kernel.project_dir%/google.key.json'
    scopes:
      - !php/const Google\Service\Sheets::SPREADSHEETS

services:
  google.client:
    class: Google\Client
    arguments: ['%google.client.config%']
  google.sheets:
    class: Google\Service\Sheets
    arguments: ['@google.client']
  service.google.sheets:
    class: Alastor141\Google\Api\Sheets
    arguments: ['@google.sheets']
```