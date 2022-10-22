<?php $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
<title>Configuration d'Eclipse IDE</title>

<BR><br>
<br>
<div align="center">
    <font class="title">
        <center>- ECLIPSE - <br>Param&eacute;trage de l'environnement de d&eacute;veloppement Eclipse et de Maya pour Windows</center>
    </font>
    <br>
    <!--<br>
    <font class="description">
        
    </font>-->
    <br>
    <br>
</div>
<br>
<hr>
<br>
<br>
<font class='title'>
    Sommaire
</font>

<a href="<?php $actual_link;?>#10">
    <h1 class='sum' id='s0'>Introduction</h1>
</a>
<a href="<?php $actual_link;?>#20">
    <h1 class='sum' id='s1'>Installation d'Eclipse</h1>
</a>
<a href="<?php $actual_link;?>#30">
    <h1 class='sum' id='s2'>Installation de PyDev</h1>
</a>
<a href="<?php $actual_link;?>#40">
    <h1 class='sum' id='s3'>Configuration de la passerelle vers Maya</h1>
</a>
<a href="<?php $actual_link;?>#41">
    <h2 class='sum' id='s4'>Côté Eclipse</h2>
</a>
<a href="<?php $actual_link;?>#42">
    <h2 class='sum' id='s5'>Côté Maya</h2>
</a>
<a href="<?php $actual_link;?>#50">
    <h1 class='sum' id='s6'>Conclusion</h1>
</a>


<br>
    
    
    
    
<dt id="10"></dt>
<h1>
    Introduction
</h1>
<P>Quand on commence &agrave; d&eacute;velopper pour maya, on suit en g&eacute;n&eacute;ral un cheminement assez lin&eacute;aire : on commence par d&eacute;velopper dans le script editor. Puis, on se rend compte de l'extr&ecirc;e rigidité de ce dernier, à tous les niveaux. Du coup, on essaye notepad++. Là, on découvre qu'on peut configurer les couleurs, que les indentations sont souvent automatiques, que vous disposez de l'auto-complétion pour vos variables, etc... Hé bien en general, l'étape suivante consiste à passer sur un veritable IDE, ou <a href=http://fr.wikipedia.org/wiki/Environnement_de_d%C3%A9veloppement>Environnement de Développement</a>. Et dans ce tuto, vous l'aurez compris, on ne va pas s'intéresser à n'importe lequel, on va s'intéresser à Eclipse.</P>

<P>L'avantage d'un IDE complet, c'est que vous pouvez travailler sous forme de projet beaucoup plus que sous forme de fichier, vous avez un projet plus modulable, et donc plus orienté objet =) En outre, une multitude d'outils vous permettrons d'augmenter votre efficacité (et de travailler dans des conditions plus agréables aussi, bien sûr ! Nous ne sommes pas des machines =), ou même plus simplement de faire des choses que vous ne pourrez pas faire avec des éditeurs de texte ou le script editor de maya (du genre envoyer un projet complet à maya - i.e. un ensemble de fichiers interagissant entre eux).
En outre, Eclipse a l'avantage de communiquer directement avec maya ! Plus besoin de sauvegarder votre script pour le sourcer ensuite dans Maya !</P>

<P>Bref, vous l'aurez compris, bosser avec un veritable ide, c'est la vie, et Eclipse en est un bon exemple ! Je vous encourage aussi à tester PyCharm, qui est un IDE très puissant pour des projets Python. Je ne l'utilise pas moi-même, parce que je n'ai pas trouvé comment le faire communiquer directement avec maya sur MacOS, mais les tests que j'ai pu faire au travail me laissent penser que c'est vraiment un super outil aussi ! N'hesitez pas à essayer par vous-mêmes !</P>

<P> Pour resumer, voici les etapes que nous allons suivre :
<UL>
<LI>Téléchargement et installation d'Eclipse</LI>
<LI>Téléchargement et installation de PyDev</LI>
<LI>Configuration de Maya</LI>
<LI>Paramétrage de Python pour Eclipse</LI>
</UL>
</P>

<dt id="20"></dt>
<h1>
    Installation d'Eclipse
</h1>


<P>Pour commencer, il va nous falloir... Eclipse =) Vous pouvez le télécharger sur www.eclipse.org. Sachez dès maintenant que la particularité d'Eclipse réside dans le fait qu'il peut être utilisé pour plein de langages, et que par défaut, il est livré "vide", en ce sens que c'est à l'utilisateur d'installer par la suite les packages dont il aura besoin en fonction de son langage de prédilection (java, c++, php, python, etc). Toutefois, vous constatez sur le site qu'il y a deja plusieurs distributions "pre-packées" en fonction des besoins de chacun. Pour ma part, j'ai pris la version pour C++ pour des raisons personnelles, mais vous pouvez prendre celle que vous souhaitez, puisqu'on viendra installer le matériel nécessaire pour développer en Python par la suite.</P>

<P>Notez d'ailleurs que la distribution d'eclipse est un standalone. Pas besoin d'installation, vous pouvez juste copier/coller votre dossier telechargé où vous voulez. En general je la copie dans C:\Program Files, histoire de rester cohérent, mais mettez le ou vous voulez.</P>

<P>A ce stade, vous pouvez déjà doube-cliquer sur le fichier eclipse.exe qui se trouve dans votre dossier Eclipse pour ouvrir votre IDE. A noter qu'il se peut qu'Eclipse vous renvoie un message d'erreur un peu incompréhensible concernant Java. En effet, Eclipse a besoin de librairies java pour tourner. 
Je ne sais pas si le <a href=http://fr.wikipedia.org/wiki/Environnement_d%27ex%C3%A9cution_Java>JRE</a> suffit, pour ma part j'installe systematiquement le <a href=http://fr.wikipedia.org/wiki/Java_Development_Kit>JDK</a>, et ca règle le problème (qui peut le plus peut le moins =). <a href=http://www.oracle.com/technetwork/java/javase/downloads/index.html>Vous pouvez le trouver ici, à l'heure où j'écris ces lignes</a>


<dt id="30"></dt>
<h1>
    Installation de PyDev
</h1>

<P>Comme je vous le disais, Eclipse est livré 'vide' par défaut (ou avec quelques presets en fonction de la distribution que vous avez pris).
Nous allons donc avoir besoin de telecharger les outils pour utiliser Eclipse comme IDE Python !
Le package Python pour Eclipse s'appelle PyDev. Pour le récuperer, vous pouvez soit passer par le site du projet PyDev directement, soit utiliser un outil livré avec Eclipse, qui s'appelle le Marketplace, et que vous pouvez trouver dans Help > Eclipse Marketplace. C'est ce que j'utilise (comme je vous disais, Eclipse est vraiment designé pour que chacun telecharge facilement ce dont il a besoin par la suite)</P>

<P>Là, dans le champ de recherche, tapez évidemment « PyDev »
Parmi les résultats, vous devriez pouvoir retrouver « PyDev – Python IDE for Eclipse » <P>


<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/00.jpg" NAME="setEclipse00" ALIGN=CENTER  BORDER=0>
	<font class='alt'>
	<br>La recherche effectuée via le Marketplace
        </font>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Cliquez ensuite, sans surprise, sur le bouton « install »
La fenêtre suivante vous propose de choisir les packages que vous voulez installer.
Inutile de rentrer dans le detail, laissez ca par defaut</P>


<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/01.jpg" NAME="setEclipse01" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>Il ne vous reste plus qu'à confirmer pour qu'Eclipse telecharge et installe automatiquement tout ca pour vous =) Il est sympa ce Eclipse !
(Quand on vous le demande, acceptez bien sûr les termes de la licence toussa toussa)</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/02.jpg" NAME="setEclipse02" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>Pour finir, il vous sera demandé de relancer Eclipse.</P>


<dt id="40"></dt>
<h1>Configuration de la passerelle vers Maya</h1>

<P>Voilà, votre eclipse possède maintenant PyDev, qui vous permet de développer en Python. Maintenant, on va s'attacher à créer la passerelle avec Maya.
Ca se passe en deux etapes, on va déjà configurer Eclipse, puis ensuite on 'ouvrira les portes' côté Maya.</P>

<dt id="41"></dt>
<h2>Côté Eclipse</h2>

<P>Vous pouvez trouver le plugin qu'il nous faut sur <a href=http://www.creativecrash.com/maya/downloads/applications/syntax-scripting/c/eclipse-maya-editor>Creative Crash, ici</a>
Il vous faut être inscrit pour pouvoir telecharger, mais l'inscription est gratuite (et je vous encourage à la faire si, par le plus grand des hasards, vous n'étiez pas déjà inscrit, ce site est une mine d'or !)</P>

<P>Vous récuperez un fichier de type .jar (chez moi, eclipseMayaEditor_2015.0.0.201405052317.jar)</P>

<P>Là encore, plusieurs méthodes d'installation, pour ma part je copie ce fichier .jar directement dans le folder d'install d'Eclipse (depend donc de où vous avez mis votre folder Eclipse).
Chez moi, c'est donc C:\Program Files\eclipse\plugins. On relance un ptit coup Eclipse, pour la forme</P>

<P>Il ne nous reste maintenant plus qu'à paramétrer le bon compiler et y intégrer l'auto-complétion de Maya. Comme vous savez, quand Maya lit du Python, il le compile (ce qui fait que vous avez des .py dans votre dossier de script, mais aussi des .pyc, qui sont les fichiers python compilés). Ca implique l'utilisation d'un compileur. Et là, on a besoin du compiler python de maya !</P>

<P>Rendez vous dans Window>Preferences pour accéder au panneau de préferences.

Là, naviguez jusqu’à PyDev/Interpreters/Python Interpreter </P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/03.jpg" NAME="setEclipse03" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>Cliquez ensuite sur le bouton « New... » en haut à gauche pour configurer un nouvel interpreter.
Dans la fenêtre suivante, nommez-le comme vous voulez (maya2013 pour moi), et gardez ce nom en mémoire, c'est par ce nom qu'on le retrouvera ensuite à la création d'un nouveau projet ! Allez ensuite browser l'interpreter qui se trouve par defaut dans C:\Program Files\Autodesk\Maya2013\bin\mayapy.exe, puis faites « ok »</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/04.jpg" NAME="setEclipse04" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>A la fenêtre suivante, Eclipse vous propose directement les repertoires contenant les librairies nécessaires à la compilation par defaut, contentez-vous de faire « ok »</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/05.jpg" NAME="setEclipse05" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Voilà, votre interpreter est configuré :</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/06.jpg" NAME="setEclipse06" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

</P>Ajoutons maintenant l'auto-complétion. Pour ceux qui ne le sauraient pas, l'auto-complétion, c'est ce dont je parlais dans l'introduction : vous commencez à écrire cmds.parentC par exemple, et l'IDE va vous suggérer automatiquement cmds.parentConstraint(). Pratique, donc =) Surtout quand vous ne connaissez pas encore trop les commandes par coeur !</P>
<P>Rendez vous dans l'onglet Predefined, dans la partie inférieure de la même fenêtre, puis cliquez à nouveau sur « new » (celui de la partie inférieure, cette fois, qui se rattache à l'onglet Predefined!)</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/07.jpg" NAME="setEclipse07" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Dans la fenêtre suivante, Eclipse vous propose de selectionner un dossier. Il attend que vous lui fournissiez le folder qui contient les infos d'auto-completion de maya. Ce folder se trouve par defaut dans C:\Program Files\Autodesk\Maya2013\devkit\other\pymel\extras\completion\, et il s'agit du folder pypredef.
Selectionnez-donc ce folder dans la fenêtre Eclipse sur laquelle nous étions, puis faites « ok »</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/08.jpg" NAME="setEclipse08" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Faites, pour finir, « ok » pour sortir des prefs Eclipse, puis redémarrez-le (rien d'obligatoire là-dedans, mais je préfère redémarrer après chaque 'gros' changement). Voilà pour la partie Eclipse ! </P>

<dt id="42"></dt>
<h2>Côté Maya</h2>

<P>Il vous faut maintenant ouvrir un port pour qu'Eclipse puisse 'communiquer' avec maya.
La commande maya à utiliser pour ouvrir un port est commandPort, et le port à ouvrir est le 7720.
Copiez-collez le code suivant dans un onglet python :</P>


<?php createCodeX("import maya.cmds as cmds
if cmds.commandPort(':7720', q=True) !=1:
    cmds.commandPort(n=':7720', eo = False, nr = True) 
");?>

<P>Vous pouvez ensuite soit le mettre dans un bouton du shelf pour l'appeler à chaque fois que vous le souhaitez, soit l'ajouter directement dans votre fichier userSetup.py, pour que ce soit fait automatiquement à chaque lancement d'une nouvelle instance de maya. Ce n'est pas l'objet de ce tuto, aussi je ne detaillerai pas trop, mais sachez que tout ce que vous inscrivez dans votre fichier userSetup sera execute au démarrage de maya.</P>

<P>
Voilà pour toute la partie « installation » à proprement parlé ! On va maintenant passer très rapidement en revue les options de base d'Eclipse (j'insiste sur le "de base" !)
Au démarrage d'Eclipse, vous notez que l'interface est assez minimaliste =)</P>


<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/09.jpg" NAME="setEclipse09" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>A partir de là, deux solutions : soit vous importez un projet déjà existant (via file>import>general>existing projects into workspace), mais ca impliquerait que vous bossez déjà probablement avec Eclipse. Donc plus vraisemblablement, vous voudrez créer un nouveau projet. 
Allez donc dans File > New > Project. Puis, choisissez – sans surprise - "PyDev Project".</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/10.jpg" NAME="setEclipse10" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>La fenêtre suivante vous demande de nommer votre projet ainsi que de choisir l'interpreter à utiliser (ainsi que d'autres options que je vous laisserai decouvrir, rien de bien complexe =)</P>

<P>Nommez votre projet comme vous voulez, mais pensez évidemment absolument à changer d'interpreter ! Souvenez-vous, j'avais appelé le mien maya2013, donc c'est sous ce nom que je le retrouve dans la liste déroulante de mes interpreters, mais il a peut-être un nom different chez vous si vous l'avez nommé differement à l'étape où on l'a créé.</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/11.jpg" NAME="setEclipse11" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>


<P>Une fois votre projet créé, Eclipse devrait détecter qu'il s'agit d'un projet python et vous propose d'ouvrir la perspective adaptée au pydev (i.e. de disposer l'espace de travail pour ce qu'il considère comme optimisé aux besoins de python). S'il ne le fait pas, sachez que ca revient au même que d'aller dans Window > show view > PyDev package explorer (le package explorer correspond un peu à votre outliner sous maya : vous y voyez tous les fichiers de votre projet)</P>

<P>Pour le reste, je vais vous laisser vous familiariser avec l'interface, on va se contenter de créer un fichier et de l'executer.

Dans la fenêtre PydevPackageExplorer, dépliez votre projet, pour constater qu'il ne contient pour l'instant que votre interpreter (il faut bien commencer quelque part =) :</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/12.jpg" NAME="setEclipse12" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Effectuez un clic droit sur votre projet, puis new>file

nommez le comme vous voulez, en prenant soin d'ajouter l'extention .py pour qu'il soit automatiquement detecte comme un fichier python :</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/13.jpg" NAME="setEclipse13" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

<P>Double-cliquez dessus pour ouvrir un fichier, puis écrivez-y le code suivant, par exemple :

<?php createCodeX("import maya.cmds as cmds
cmds.warning( 'hello World ! ' )
");?>


<P>On va maintenant envoyer ça à Maya. Assurez-vous bien d'avoir le bon port ouvert (reportez-vous à la partie qui en parle au dessus si necessaire).

Si le paramétrage de l'auto-complétion s'est bien passé, vous devriez avoir l'auto-completion à ce niveau la (que je ne peux pas vous montrer avec le super utilitaire de capture d'écran de windows…. =).
Si ce n'est pas le cas, revenez sur vos pas, à la partie où on paramètre l'auto-completion, et si ca ne fonctionne pas, n'hesitez pas à poster dans les commentaires.
Mais à priori, en commencant à taper cmds.war, Eclipse devrait vous suggerer cmds.warning() de lui-même !

Quoiqu'il en soit, voyons comment envoyer notre super script à maya. Si l'installation du plugin récupéré sur creativeCrash a fonctionnée, vous devriez avoir une série de boutons supplémentaires dans la barre d'outils :

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/14.jpg" NAME="setEclipse14" ALIGN=CENTER  BORDER=0>
    </div>
</P>
<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/15.jpg" NAME="setEclipse15" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

Le bouton tout à droite, en forme de prise électrique, permet de connecter Eclipse à Maya. Si vous cliquez dessus, et que vous retournez sous Maya, votre script editor devrait vous renvoyer :</P>

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/16.jpg" NAME="setEclipse16" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

Il ne vous reste plus ensuite qu'à executer votre script, avec une des trois touches de gauche (lisez leurs infobulles respectives pour plus de details) !

<P><div align='center' class='content'>
        <img class='content' SRC="t/set_eclipse/img/17.jpg" NAME="setEclipse17" ALIGN=CENTER  BORDER=0>
    </div>
    <BR CLEAR=LEFT>
</P>

   
<dt id="50"></dt>
<h1>
    Conclusion
</h1>

<P>Voila pour l'installation d'Eclipse. Toutefois, nous n'avons fait que grater la surface. Je vous invite à personnaliser vos couleurs en telechargeant des packages de colorisation syntaxique via le marketPlace, à définir vos raccourcis clavier, etc.. bref, Eclipse va faire prendre à vos projets une dimension autre que ce que vous pouviez avoir jusqu'alors avec un editeur de texte plus classique</P>

<P>En outre, ce qui est proposé là en termes de configuration est assez 'géneraliste', et il se peut que vous ayez d'autres cas de figure (en fonction de programmes déjà installés sur votre ordinateur par exemple), mais il est aussi difficile pour moi que ce serait soporifique pour vous de couvrir tous les setups possibles. N'hesitez donc pas a faire part de difficultés rencontrées dans les commentaires =)





<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
<BR>
