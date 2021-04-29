# wkhtml2pdf saas

## config.yml
Fichier contenant les paramétrages du service. Ce fichier doit être monté dans le dossier `/data` du container.

Les clés peuvent être générées en UUIDv4.

Les paramètres de WK sont ceux de l'application wkhtmltopdf et wkhtmltoimage
```yaml
account:
    "<account_key>":
        name: "Account name"
        private-key: "<sign_jwt_key>"
        enable: true
    "demo":
        name: "Account demo"
        private-key: "my private key"
        enable: false
wk:
    common:
        javascript-delay: 500
        no-stop-slow-scripts: false
    pdf:
        title: "Html to Pdf Generator"
        orientation: "Portrait"
        page-size: "A4"
        margin-top: 20
        margin-bottom: 20
        margin-left: 15
        margin-right: 15
        no-background: false
        lowquality: true
    image:
        format: png
```

## Execution
To run on 8888 port and `config.yml` in `./data`
```shell
docker run -it --rm -v $(pwd)/data:/data -p 8888:80 wyzenrepo/wkhtml2pdf-saas
```
