<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{config('l5-swagger.documentations.'.$documentation.'.api.title')}}</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}" >
  <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}" sizes="32x32" />
  <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}" sizes="16x16" />
  <style>
    html
    {
        box-sizing: border-box;
        overflow: -moz-scrollbars-vertical;
        overflow-y: scroll;
    }
    *,
    *:before,
    *:after
    {
        box-sizing: inherit;
    }

    body {
      margin:0;
      padding-bottom: 20px;
      background: #fafafa;
    }

    .swagger-ui .topbar {
        background-color: white;
        background: url(https://practice.uffs.edu.br/images/banner-rotulo.png) 0% 50%; background-size: cover; background-position: center top;
        height: 450px;
        margin-top: -50px;
    }

    .swagger-ui .topbar .topbar-wrapper {
        display: none;
    }    

    .swagger-ui .topbar .download-url-input {
        border-color: #007C9D !important;
    }

    .swagger-ui .topbar .download-url-button {
        background-color: #007C9D !important;
    }

    #swagger-ui > section > div.swagger-ui > div:nth-child(2) > div.information-container.wrapper > section > div > div > div.description > div > p {
        font-size: 1.1em;
        padding: 5px 0 5px 0;
    }

    #swagger-ui div.information-container.wrapper a {
        font-size: 1.2em;
        color: black;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }
  </style>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>

<div id="swagger-ui"></div>

<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"> </script>
<script src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}"> </script>
<script>
window.onload = function() {
  // Build a system
  const ui = SwaggerUIBundle({
    dom_id: '#swagger-ui',

    url: "{!! $urlToDocs !!}",
    operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
    configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
    validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
    oauth2RedirectUrl: "{{ route('l5-swagger.'.$documentation.'.oauth2_callback') }}",

    requestInterceptor: function(request) {
      request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
      return request;
    },

    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],

    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],

    layout: "StandaloneLayout",

    persistAuthorization: {!! config('l5-swagger.defaults.persist_authorization') ? 'true' : 'false' !!},
  })

  window.ui = ui
}
</script>
</body>

</html>
