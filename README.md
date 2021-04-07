Sitemas de login e verificação de admin ficaram cerca de 80% concluídos.
Demais implementações foram concluídos.

Para verificar como o sistema se comportaria com o usuário sem ser admin é necessário mudar o return da função isAdmin na classe RoutingSystem, no arquivo include/system/sys.routing.class.inc.php;
Para configurar o banco de dados é preciso alterar o arquivo web/config.php

Para o funcionamento do sistema é necessário mudar o parâmetro DocumentRoot: do apache para a pasta 'web' na pasta que o projeto está alocado. 

Exemplo:

DocumentRoot "C:/xampp/htdocs/spinProject/web"
<Directory "C:/xampp/htdocs/spinProject/web">



O arquivo spinProject.postman_collection.json possui os cruds que podem ser importados no postman para facilitar os testes individualizados das funções.


O sistema para validação de segurança das APIS não foi implemtendao pois demandaria um esforço e tempo além do proposto.

O arquivo spinProject.SQL possui a estrututa do banco de dados para ser importada, sem dados.

Demais dúvidas fico a disposição no email: arthurvilmar.santos@gmail.com ou no número: (47) 9 9199-5670