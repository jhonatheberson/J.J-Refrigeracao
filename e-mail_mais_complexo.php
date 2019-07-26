Ir para o conteúdo
Características
O negócio
Explorar
Marketplace
Preços

Search

Entrar ou Inscrever-se
241 1,525 6,333 Synchro / PHPMailer
bifurcado do
PHPMailer / PHPMailer
Solicitações de Pull de Código 0 Projetos 0 Wiki Insights
Junte-se ao GitHub hoje
OGitHub é o lar de mais de 28 milhões de conjuntos de dados para hospedar e examinar código, gerenciar projetos e construir juntos.

PHPMailer /get_oauth_token.php
31493b6  on 7 Sep 2017
@Synchro Limpeza do Synchro Big para o estilo de codificação do Symfony e php-cs-fixer, consulte # 1148
@Synchro @ sherryl4george @fbonzon

145 linhas (130 sloc)  4,75 KB
<? php
/ **
 * PHPMailer - Criação de e-mail PHP e classe de transporte.
 * Versão do PHP 5.5
* @pacote PHPMailer
* @see https://github.com/PHPMailer/PHPMailer/ O projeto PHPMailer GitHub
* @author Marcus Bointon (Synchro / coolbru) <phpmailer@synchromedia.co.uk>
* @author Jim Jagielski (jimjag) <jimjag@gmail.com>
* @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
* @author Brent R. Matzelle (fundador original)
* @copyright 2012 - 2017 Marcus Bointon
* @copyright 2010 - 2012 Jim Jagielski
* @copyright 2004 - 2009 Andy Prevost
* @license http://www.gnu.org/copyleft/lesser.html GNU Licença Pública Geral Menor
 * @note Este programa foi distribuído na esperança de que seja útil - SEM
* QUALQUER GARANTIA; sem mesmo a garantia implícita de COMERCIALIZAÇÃO ou
 * ADEQUAÇÃO UM UM DETERMINADO FIM.
 * /
/ **
 * Obtenha um token OAuth2 de um provedor OAuth2.
 * * Instale este script no seu servidor para ser acessível
 * como [https / http]: // <yourdomain> / <pasta> /get_oauth_token.php
 * por exemplo: http: //localhost/phpmailer/get_oauth_token.php
 * Assegure-se de que as dependências precisam instaladas com 'composer install'
 * * Configure um aplicativo na sua conta do Google / Yahoo / Microsoft
 * Defina o endereço do script como a URL do redirecionamento do aplicativo
 * Se nenhum token de atualização para o executar este arquivo,
 * revogue o acesso ao seu aplicativo e execute o script novamente.
 * /
namespace  PHPMailer \ PHPMailer ;
/ **
 * Aliases para Classes de Provedores da Liga
 * Certifique-se de tê-los adicionado ao seu compositor.json e execute `composer install`
 * Muito por onde escolher aqui:
* @see http://oauth2-client.thephpleague.com/providers/thirdparty/
 * /
// @see https://github.com/thephpleague/oauth2-google
use  League \ OAuth2 \ Client \ Provider \ Google ;
// @see https://packagist.org/packages/hayageek/oauth2-yahoo
use o  Hayageek \ OAuth2 \ Cliente \ Provedor \ Yahoo ;
// @see https://github.com/stevenmaguire/oauth2-microsoft
use  Stevenmaguire \ OAuth2 \ Client \ Provider \ Microsoft ;
if ( ! isset ( $ _GET [ ' código ' ]) &&  ! isset ( $ _GET [ ' provedor ' ])) {
? >
< html >
< Corpo > Select Provider: < br />
< A  href = ' ? Provider = Google " > Google </ a > < br />
< A  href = ' ? Provider = Yahoo ' > Yahoo </ a > < br />
< A  href = ' ? Provider = Microsoft " > Microsoft / Outlook / Hotmail / Live / Office365 </ a > < br />
</ body >
</ html >
<? php
saída ;
}
require  ' vendor / autoload.php ' ;
session_start ();
$ providerName  =  ' ' ;
se ( array_key_exists ( ' Provedor ' , $ _GET )) {
    $ providerName  =  $ _GET [ ' provedor ' ];
    $ _SESSION [ ' provider ' ] =  $ providerName ;
} Elseif ( array_key_exists ( ' Provedor ' , $ _SESSION )) {
    $ providerName  =  $ _SESSION [ ' provedor ' ];
}
if ( ! in_array ( $ providerName , [ ' Google ' , ' Microsoft ' , ' Yahoo ' ])) {
    exit ( ' Apenas os provedores do Google, Microsoft e Yahoo OAuth2 são atualmente suportados neste script ' );
}
// Este detalhes são um aplicativo para configurar o console do desenvolvedor do Google,
// OU QUALQUÉR provedor that rápido Você esteja usando.
$ clientId  =  ' RANDOMCHARS-----duv1n2.apps.googleusercontent.com ' ;
$ clientSecret  =  ' RANDOMCHARS ----- lGyjPcRtvP ' ;
// Se esse URL não funcionar, defina-o manualmente para o script da URL
$ redirectUri  = ( isset ( $ _SERVER [ ' HTTPS ' ])? ' https: // ' : ' http: // ' ) .  $ _SERVER [ ' HTTP_HOST ' ] .  $ _SERVER [ ' PHP_SELF ' ];
// $ redirectUri = 'http: // localhost / PHPMailer / redirect';
$ params  = [
    ' clientId '  =>  $ clientId ,
    ' clientSecret '  =>  $ clientSecret ,
    ' redirectUri '  =>  $ redirectUri ,
    ' accessType '  =>  ' offline '
];
$ options  = [];
$ provider  =  null ;
switch ( $ providerName ) {
    caso  ' Google ' :
        $ provider  =  new  Google ( $ params );
        $ options  = [
            ' scope '  => [
                ' https://mail.google.com/ '
            ]
        ];
        pausa ;
    caso  ' Yahoo ' :
        $ provider  =  new  Yahoo ( $ params );
        pausa ;
    caso  ' Microsoft ' :
        $ provider  =  new  Microsoft ( $ params );
        $ options  = [
            ' scope '  => [
                ' wl.imap ' ,
                ' wl.offline_access '
            ]
        ];
        pausa ;
}
if ( null  ===  $ provider ) {
    saída ( ' provedor ausente ' );
}
if ( ! isset ( $ _GET [ ' código ' ])) {
    // Se não tiver um código de autorização, receba um
    $ authUrl  =  $ provider -> getAuthorizationUrl ( $ opções );
    $ _SESSION [ ' oauth2state ' ] =  $ provider -> getState ();
    cabeçalho ( ' Localização: '  .  $ authUrl );
    saída ;
// Verificação do contexto em relação à prioridade para mitigar o ataque CSRF
} Elseif ( vazio ( $ _GET [ ' estado ' ]) || ( $ _GET [ ' estado ' ] ! ==  $ _SESSION [ ' oauth2state ' ])) {
    unset ( $ _SESSION [ ' oauth2state ' ]);
    unset ( $ _SESSION [ ' provedor ' ]);
    saída ( ' estado inválido ' );
} mais {
    unset ( $ _SESSION [ ' provedor ' ]);
    // Tente obter um token de acesso (usando uma autorização de código de autorização)
    $ token  =  $ provider -> getAccessToken (
        ' authorization_code ' ,
        [
            ' code '  =>  $ _GET [ ' código ' ]
        ]
    );
    // Use isso para interagir com uma API no nome dos usuários
    // Use isso para obter um novo token de acesso ao antigo expirar
    echo  ' Atualizar token: ' , $ token -> getRefreshToken ();
}
© 2018 GitHub , Inc.
Termos
Privacidade
Segurança
Status
Socorro
Entre em contato com o GitHub
API
Treinamento
fazer compras
Blog
Sobre
Pressione h para abrir um cartão de visita com mais detalhes.
