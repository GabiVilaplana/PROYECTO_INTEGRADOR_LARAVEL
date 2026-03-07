# Seguridad y Cifrado HTTPS

**Objetivo:** Garantizar la confidencialidad de los datos de los usuarios mediante cifrado SSL.

## Implementación Técnica:

### 1. Certificados SSL (Let's Encrypt)
Se integró **Certbot** para la generación y renovación automática de certificados gratuitos emitidos por Let's Encrypt.

### 2. Configuración de Nginx
El servidor web (Nginx) se configuró como un **Proxy Inverso**:

-   **Redirección Forzosa**: Todo el tráfico entrante por el puerto 80 (HTTP) se redirige permanentemente (301) al puerto 443 (HTTPS).
-   **Protocolos Modernos**: Se habilitaron TLS 1.2 y 1.3, desactivando protocolos antiguos y vulnerables para cumplir con los estándares de seguridad actuales.
