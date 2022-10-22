<?php include_once 't/head.php';?>
<dt id="10"></dt><h1>Introduction</h1>

<P>Beaucoup de tutos sont disponibles sur internet pour apprendre le Python, dont certains très très complets (je vous renvoie au
<A HREF="http://openclassrooms.com/courses/apprenez-a-programmer-en-python">site du zero</A> ou aux excellentes <A HREF="https://www.youtube.com/user/Pythonneries">Pythonneries</A>
). En revanche, les tutos pour apprendre le python appliqué à Maya sont plus rares, et il peut parfois être difficile de se plonger dans un domaine (ici Python) sans aucune accroche concrète avec ses propres besoins. Dans ce tuto, nous allons voir les bases du Python (et j'insiste très lourdement sur le fait qu'il s'agisse vraiment des bases ! ), tout en essayant de les appliquer au plus vite à Maya pour y voir une utilité concrète. Bien sûr, ceux d'entre vous qui sont déjà familiers avec le Python risquent fort de s'ennuyer, il est probable aussi que certains sautent au plafond devant quelques abus de langage ou vulgarisations dont nous userons ici. Pour les autres, j'espère que tout ça pourra vous être utile pour vous lancer dans le monde merveilleux qu'est le script sous maya =)</P>
<P>
Pour l'aspect technique, vous n'avez besoin de rien d'autre qu'une installation valide de maya, en tout cas pour l'instant. Par la suite, si vous souhaitez approfondir, je vous encourage à rapidement passer vers un éditeur de texte spécialisé au pire (notepad++ sur pc, komodo/bbEdit/etc sur mac, ce sera largement suffisant pour un bon moment =), ou au mieux vers un IDE complet (Eclipse par exemple). Pour les besoins de ce tuto, nous nous contenterons du script editor de maya (que vous pouvez trouver sous Window>General Editors>Script Editor). Les plus perspicaces auront compris que la partie supérieure du script editor fait office de listener (ou on verra donc ce que maya fait. La communication s'y fait donc dans le sens maya => utilisateur) tandis que la partie inférieure est réservée à l'utilisateur (nous sommes cette fois dans le sens utilisateur => maya)</P>

<P>Ceci étant dit, rentrons dans le vif du sujet !</P>


<dt id="20"></dt><h1>Premiere partie : Les bases du Python</h1>
<dt id="21"></dt><h2>Les variables</h2>

<P>Pour commencer, il convient de définir quelques concepts propres au Python, et de manière générale, à n'importe quel langage informatique. Un de ces concepts fondamentaux, c'est les variables.
Une variable est, vulgairement, un morceau de la mémoire de l'ordinateur dans lequel on stock une information, pour venir la lire ou l'editer par la suite. Par exemple, j'ecris dans mon script editor :</P>

<?php createCodeX("boite = 10
");?>


<P>Si j'execute cette ligne (sélectionnez la, puis appuyez sur ctrl+Entree. Le fait de sélectionner la ligne avant vous évite de supprimer tout ce que vous avez surligné au moment de l'execution), je me réserve dans un coin de la mémoire de mon ordinateur la valeur 10, que je pourrai rappeler à chaque fois que je demande à l'ordinateur de m'afficher le contenu de cet endroit de la mémoire, le contenu de ma boite. 
Ainsi, si je demande a maya d' « imprimer » le contenu de ma variable (avec la fonction 'print') et que je l'execute :
</P>
<?php createCodeX("print boite");?>
<P>je recupere la valeur 10 dans la partie superieure (le listener)</P>

<?php addImage("00.png");?>

<P>À noter l'utilisation de la commande « print », sans doute la commande la plus utile en python ! Cette commande permet d' « imprimer » tout et n'importe quoi, du contenu d'une variable (comme ici) à la longueur d'une chaine de caractères… La syntaxe est très simple, c'est 'print' suivi de ce qu'on veut imprimer =)</P>

<P>Il est très important de comprendre que quand Python lit une ligne contenant une variable, il la remplace par ce qu'elle contient.</P>

<P>Par exemple, apres avoir créé ma variable boite = 10, je peux tres bien taper « boite+5 », et python me retournera 15. Il a bien remplacé le 'boite' par sa valeur. Voilà tout l'intérêt et la puissance des variables !
</P>

<P>Il est à noter également que les variables peuvent être de plusieurs types, et que chaque type a des specifités bien différentes ! Avant de les décrire, j'attire l'attention sur le fait que definir une variable suffit à definir son type également. Python a un systeme de déclarations de variables dit 'dynamique', c'est-à-dire que chaque fois que l'on crée une variable (ou que l'on « déclare » une variable), python va comprendre directement de quel type il s'agit. A l'inverse, en mel par exemple, il faut déclarer de quel type est la variable au moment de sa création, comme suit :</P>

<?php createMELCodeX("string \$maVariable = 'hello';");?>

<P>On note la presence du mot 'string' avant la variable ! Ca peut paraitre anodin, mais c'est tres appréciable de ne pas avoir à déclarer à chaque fois de quel type est la variable, quand on a 300 variables différentes dans un programme, et qu'on n'est pas familiarisé avec ce concept !</P>


<P>Les types de variable les plus fréquents sont :</P>
<OL>
    <LI>string : c'est une chaine de caracteres, du texte, en d'autres mots. Par exemple « variable='je suis une variable string'. La syntaxe est donc : la declaration de la variable / le signe egal / le contenu de la variable, entre guillemets pour signaler a python que c'est de type string. </LI>
    <LI>int : integral (integer en anglais, entier en francais), un nombre entier. Meme syntaxe, on declare la variable, on ajoute =, et on donne un nombre entier, pour indiquer que c'est une variable de type integral, entier. Donc 0, 1, 43, 84, …
</LI>
    <LI>float : nombre a virgule flottante, nombre decimal. Meme syntaxe. maVariableFloat = 3.1415</LI>
    <LI>list : liste de plusieurs elements ,soit plusieurs mots, plusieurs chiffres, etc. Pour creer une liste vide, on ecrit maListe = []. Par exemple :</LI>
    
<?php createCodeX("maListeVide = []
maListeDeStrings = [\"hello\", \"bonjour\", \"pasteque\"]
maListeDeFloats = [1.23, 3.14, 12.3523]");?>
    
</OL>

<P>On note que le seul trait commun entre ces 3 variables est que ce sont des listes, et qu'on reconnait ca aux crochets. Juste deux crochets creent une liste vide, et deux crochets, avc chaque element separe par une virgule, c'est une liste contenant des elements, quel que soit leur type.</P>


<dt id="22"></dt><h2>Les fonctions</h2>

<P>Python, comme bien d'autres langages, permet de créer ses propres fonctions. Mais il est également doté d'une pléthore de fonctions déjà écrites et disponibles nativement, parce quasiment indispensables a n'importe quel programme ! (inutile de re-inventer la roue a chaque nouveau programme)
Par exemple, pour recuperer des informations concernant le type d'une variable variables, je peux exécuter :</P>

<?php createCodeX("maVariable = 'hello world'
print type(maVariable)");?>

<P>Python me renvoie automatiquement le type de variable, donc soit int, soit float, soit string, etc. Ici, je recupererai bien type 'str', qui signifie que ma variable est de type string</P>

<P>En vrac, voici quelques autres fonctions déjà pre-ecrites par jean-michel python (je ne m'eternise volontairement pas sur les fonctions pour aller au plus vite au niveau ou on pourra faire des choses concrètes dans maya)
len(boite) : renvoie la longueur de la variable. Par exemple, si je dis:
maVariable = Natation
alors la fonction len(maVariable) me retournera la valeur 8. En effet, le mot natation contient bien 8 lettres.
</P>

<P>print, en un sens, est aussi une variable. Je demande a maya d'imprimer le contenu de ma variable avec :
print maVariable.
</P>

<P>De même, si je veux transformer le contenu de la variable boite, qui vaut 10, en une variable de type string, donc chaine de caractère, je peux appeler la fonction str (pour 'string'), avec la syntaxe suivante :</P>

<?php createCodeX("boite = str(boite)
# je mets a jour le contenu de ma boite, et je lui dit qu'a partir de maintenant, il prend la valeur de '10' en chaine de caractère");?>
<P>Bien sur, il ne va pas convertir ''10'' en ''dix'' ! Mais pour python, a partir de ce moment la, la variable sera considérée comme du texte. Ca peut sembler abstrait pour l'instant, mais vous comprendrez vite l'intérêt.</P>


<P>Il existe une infinité de fonctions, et toutes ne sont pas utiles. Le mieux, c'est que vous cherchiez au fur et a mesure de vos besoins sur internet, et voici déjà une liste des principales (qui peut sembler incompréhensible, mais vous verrez qu'une fois que vous les aurez utilisé, vous comprendrez mieux leur interet =)</P>

<UL>
<LI>split () : permet de couper en plusieurs parties une variable. Par exemple :
<?php createCodeX("maVariable = adresse@domaine.com
maVariable.split('@')");?>
Le résultat retourné aura séparé ce qui est avant de ce qui est apres le @, et retourne le contenu sous forme d'une liste,
le premier element de la liste etant ce qui est avant le @, le second ce qui vient apres le @</LI>
<LI>title()</LI>
<LI>capitalize()</LI>
<LI>upper()</LI>
</UL>
<P>
... sont autant de fonctions utiles pour manipuler le texte (le passer en majuscules, en minuscules, ne passer que la premiere lettre en majuscule, etc.

La fonction replace() est tres utile egalement pour remplacer des elements d'une variable. Par exemple:</P>
<?php createCodeX("adresse = c:/Users/Documents/maMusique
adresse.replace ('/', '\')
# le resultat retourné me renverra le meme chemin, en ayant remplace tous les / par des \"");?>

<P>Attention, ne testez pas la fonction 'replace' en copiant/collant l'exemple ci-dessus, il y a un petit piege pour ce cas precis ^^</P>




<dt id="30"></dt><h1>Seconde partie : Application a Maya</h1>
	<dt id="31"></dt><h2>Import des modules</h2>

<P>Nous y voila, on va pouvoir dès a présent appliquer ces concepts à maya pour faciliter notre travail !</P>

<P>Pour commencer, on va voir comment tout ca prend forme dans maya. Python est un langage beaucoup utilisé en grande partie parce que chaque programme peut y ajouter ses propres librairies. Par exemple, pour maya, on a besoin de commandes assez specifiques a la 3d, comme créer une sphere, ouvrir l'hypershade, etc… Toutes ces commandes specifiques a maya sont contenues dans une bibliotheque qu'on va appeler en debut de script a chaque fois. On declare qu'on va utiliser le module maya.cmds, on ecrit donc :</P>

<?php createCodeX("import maya.cmds");?>

<P>Apres quoi, on peut utiliser n'importe quelle commande de maya, comme polySphere(), qui va créer une sphere. Seulement, c'est un peu lourd d'ecrire a chaque action maya.cmds.nomDeLaction. Du coup, ce qui se fait tres souvent est d'ecrire :</P>

<?php createCodeX("import maya.cmds as cmds");?>

<P>On constate au passage que ''import'' etant un des mots clé de python, il apparait en couleur. Dans cette ligne, on voit donc qu'on importe le module maya.cmds, mais 'en tant que' (le 'as' ici) cmds. 
Une fois cette ligne ecrite en debut de script, on peut ecrire simplement cmds.polySphere() pour creer une sphere, plus besoin du maya.cmds ! Bien sur, cmds est une convention, mais vous pouvez tout aussi bien écrire import maya.cmds as coucou, et prefixer ensuite toutes vos commandes maya comme ceci : coucou.polySphere().
Il existe une multitude de modules, et on n'utilisera pas seulement le module de maya. Le module os, par exemple, permet d'effectuer des actions en python pour creer, renommer, deplacer, etc… des dossiers. Le module random permet de generer des nombres aleatoires, tandis que le module maya.mel permet de faire appel a des commandes MEL, etc etc.
</P>

<P>Bref, quel que soit le script, il est indispensable, dans 99.9% des cas, d'importer un ou plusieurs modules, en fonction de nos besoins ! Pour tous les exemples qui vont suivre, « import maya.cmds as cmds » sera probablement largement suffisant ! Et le 'as cmds' est d'ailleurs la convention que Autodesk utilise dans sa documentation (vous rencontrerez aussi parfois 'import maya.cmds as mc', que je vous deconseille d'adopter. Si tout le monde utilise une certaine syntaxe, c'est plus facile, sur le long terme, d'utiliser la meme.. en tout cas de mon experience).
</P>

    <dt id="32"></dt><h2>Comment connaitre le nom des fonctions</h2>
    
<P>Pour connaitre les commandes python de la bibliotheque maya.cmds, on a principalement deux possibilités :</P>

<OL>
    <LI><P>Dans le script editor, on peut voir la 'traduction' de chaque action qu'on effectue dans le viewport. Par exemple, a la creation d'une sphere, on constate que le script editor nous renvoie ceci :</P>
    <?php addImage("09.png", "Je vous invite a desactiver la creation interactive des polygons, pour cet exemple")?>
    <P>Chaque action effectuée dans maya est donc retranscrite dans le script editor (il peut etre utile de cocher, dans le script editor, sous le  menu History, la commande Echo All Commands, pour que le script editor ecrive aussi certaines commandes qu'il n'ecrirait pas normalement). On constate, a la vue de la syntaxe, que c'est du mel, mais ce n'est pas grave, puisque la syntaxe python est quasiment identique, si ce n'est qu'on prefixe avec « cmds. » </P>

<P>Ainsi, pour notre exemple de creation d'une sphere, on constate que la commande utilisée est « polysphere », suivi de caracteres derriere dont on ne s'occupe pas pour l'instant !</P>
    </LI>
    <LI><P>On se refere a <U>l'excellente</U> <A HREF="http://download.autodesk.com/global/docs/maya2014/en_us/index.html?=contextId=BULLETNODES">documentation de Maya</A> ! Vous pouvez y acceder via ce lien, sur le site d'autodesk directement via google, ou bien en appuyant sur F1 dans maya. Enfin, vous pouvez aussi telecharger l'aide complete hors-ligne si vous souhaitez scripter sans acces a internet !</P>
<P>Sur le site, ce qui nous interesse se passe dans la colonne de gauche, tout en bas, dans la rubrique Technical Documentation. Depliez le menu Technical Documentation pour arriver a la rubrique CommandsPython !
Tout est la ! Toutes les commandes disponibles en python sous maya sont regroupées ici ! Vous pouvez d'ors et deja naviguer jusqu'a la commande « polySphere » et cliquer dessus pour voir ce que maya nous en dit. Une fois sur la page de la commande polySphere, vous pouvez meme descendre en bas de page pour voir des exemples proposes par autodesk :</P>
    <?php addImage("11.png")?>
    <P>J'en profite pour vous signaler que chaque ligne qui commence par # , en python, est une ligne de commentaires, qui ne sera donc pas interpretee par python. Nous reviendrons sur les commentaires plus tard.</P>
    </LI>
</OL>

<P>En conclusion, retenez bien que vous pouvez trouver les infos dont vous avez besoin dans maya ET sur le site d'autodesk. En general, on combine ces deux sources d'info, on commence par regarder ce que le script editor dit, puis on se refere a la doc pour avoir la syntaxe exacte ainsi que toutes les precisions et subtilites de la commande qu'on veut utiliser. 
</P>



    <dt id="33"></dt><h2>Un peu de syntaxe</h2>

<P>Nous allons maintenant décortiquer l'exemple ci dessus de la polySphere et en partant de la, comprendre la syntaxe que maya attend de nous dans la quasi-totalite des cas de figure</P>
<P>Comme visible dans le listener, la commande de creation d'une sphere en mel ressemble a ceci :</P>
<P>
<font color = "red">polySphere</font> -<font color = "blue">r</font> <font color = "green">1</font> -<font color = "blue">sx</font> <font color="green">20</font> -<font color="blue">sy</font> <font color="green">20</font> -<font color = "blue">ax</font> <font color = "green">0 1 0</font> -<font color = "blue">cuv</font> <font color = "green">2</font> -<font color = "blue">ch</font> <font color="green">1</font>;
</BR>
que l'on peut generaliser de cette maniere :
</BR>
<font color = "red">nomDeLaFonction</font> -<font color = "blue">option1</font> <font color = "green">valeur</font> -<font color = "blue">option2</font> <font color="green">valeur</font> -<font color="blue">option3</font> <font color="green">valeur</font> -<font color = "blue">option4</font> <font color = "green">valeur</font>;
</P>
<P>Et vu comme ca, ca fait effectivement d'avantage sens, et ca ressemble un peu moins a rien.
La syntaxe est donc : le nom de la fonction, suivi des eventuelles options, que l'on appelle des Flags. Les flags, c'est les 'precisions' dont maya a besoin pour savoir quoi faire. Il est toutefois a noter que dans la majorite des cas, les flags sont optionnels, vous n'utilisez que ceux pour lesquels la valeur par defaut ne vous va pas, et que vous souhaitez par consequent changer. Par exemple, je peux creer une sphere avec la seule commande polysphere, mais je peux aussi avoir besoin de donner un radius autre que celui par defaut, un nombre de subdivisions moins eleve, etc etc. Toutes ces informations viennent dans les flags. Encore une fois, pas besoin de preciser tous les flags a chaque fonction (et heureusement ! Jetez-donc un oeil a la commande "file" et vous comprendrez =), puisque chaque flag a une valeur par defaut, mais vous verrez que bien souvent vous aurez besoin de donner pas mal de precisions ! Vous constaterez aussi dans la doc que chaque flag peut etre utilisé avec une version abregee, et que le script editor renvoie les abreviations, pas les mots complets (par exemple, r pour radius, ou encore sx pour subdivisions X).
</P>

<?php addImage("10.png", "Extrait de l'aide de Maya")?>

<P>Sur cet extrait de la documentation, on constate que tout est super expliqué, chaque flag est expliqué, entre parentheses a cote du nom du flag, on voit qu'il y a la version en abrege, dans la colonne argument type, on nous indique le type de donnees attendues (on constate que par exemple, 'subdivisionsX' attend une valeur de type int, donc si je lui donne une variable string, ca ne va pas lui plaire ! )
</P>

<p>Ensuite, on constate que la syntaxe est proche du mel, a savoir fonction - flags
La fonction se donne au depart, avec le prefixe cmds, puis entre parentheses, les flags. Si on compare, on aura donc :</BR>

Mel (et donc script editor) : <font color = "red">polySphere</font> -<font color = "blue">r</font> <font color = "green">1</font> -<font color = "blue">sx</font> <font color = "green">20</font> -<font color = "blue">sy</font> <font color = "green">20</font> -<font color = "blue">ax</font> <font color = "green">0 1 0</font> -<font color = "blue">cuv</font> <font color = "green">2</font> -<font color = "blue">ch</font> <font color = "green">1</font></BR>
Python (apres l'import du module maya.cmds en tant que cmds) : <font color = "red">cmds.polySphere</font> (<font color="blue">r</font>=<font color = "green">1</font>, <font color = "blue">sx</font>=<font color = "green">20</font>, <font color = "blue">sy</font>=<font color = "green">20</font>, <font color = "blue">ax</font>=<font color = "green">(0,1,0)</font>, <font color = "blue">cuv</font>=<font color = "green">2</font>, <font color = "blue">ch</font>=<font color = "green">1</font>)
</p>

<P>Voila, ca peut vous sembler premature, mais je pense que nous avons là tout ce dont nous avons besoin pour passer a une phase plus pratique ! De toute facon, le script, ca vient en pratiquant, il faut vous y faire =)</P>



<dt id="40"></dt><h1>Exercice 1 - renommer un objet</h1>
    
<p>Comme precise dans le paragraphe precedent, la pratique du python est indissociable de la theorie, et les deux doivent aller de paire. Dans cette optique, a partir de maintenant, la suite du tuto consistera en une serie d'exercices que nous pouvons faire ensemble. De cette maniere, on commencera a assimiler les concepts qu'on a deja vu, et en rajoutter au fur et a mesure au gre de nos besoins ! Je vous invite aussi a proposer des idees de scripts 'basiques' par mail, que vous souhaiteriez voir traites ici. Mais sans plus tarder, place au premier exercice</p>

    <dt id="41"></dt><h2>Principe de base</h2>

<P>Pour s'exercer un peu a la manipulation des variables sous maya, nous allons creer un programme qui permet d'ajouter un suffix dans le nom d'un objet de l'outliner. Ca peut paraitre inutile parce que ca revient au meme que de double-cliquer dans l'outliner pour renommer l'objet, mais nous verrons dans une derniere partie comment etendre cette simple operation a 10, 50 ou meme 1000 objets de l'outliner, sans avoir a le faire manuellement !</P>

<P>Pour la premiere partie de cet exercice, nous avons tous les elements necessaires ! Comme bien souvent, il y a 100 manieres de proceder, et malheureusement deja un piege =)
Pour le debut, rien de sorcier, on importe les modules dont on a besoin. Ici, maya.cmds :
</P>
<?php createCodeX("import maya.cmds");?>

<P>Ensuite, inutile de foncer tete baissee. Il est tres important, avant de commencer un nouveau script, de faire le bilan des elements dont vous aurez besoin. Ca peut vous sembler fastidieux, mais je vous assure que ca permet un enorme gain de temps par la suite et ca vous aidera a produire un ensemble structure et coherent. Voyons donc ce dont on va avoir besoin :</P>

<OL>
    <LI>l'objet qu'on a selectionné</LI>
    <LI>le suffixe qu'on veut lui rajouter</LI>
    <LI>le nouveau nom de l'objet, composé donc de l'ancien + du suffixe</LI>
</OL>

<P>Pour faciliter le process, au debut du moins, je vous encourage a creer une variable pour chaque element. Ca devrait nous donner quelque chose dans ce genre :</P>

<?php createCodeX("selectedObject = 'objectToRename'
suffix = 'suffix'
newName = selectedObject+suffix");?>

<P>J'attire des a present votre attention sur un certain nombre de points :
On constate que les deux premieres variables sont de type 'string', puisqu'entre guillemets. Quant a la troisieme variable, aucun guillemet, puisqu'on veut additionner des variables (qui contiennent des strings, donc). Quand, dans la variable newName, j'additionne les deux autres variables, il faut comprendre que l'ordinateur va remplacer les variables par leur contenu, a savoir ici 'objectToRename' et '_suffix'. Et pour finir, vous l'aurez compris, tout ca est juste pour y voir plus clair, il n'y a aucun objet dans ma scene qui s'appelle 'objectToRename'. Voyons donc comment recuperer le nom de l'objet qu'on a selectionne et le stocker dans une variable !
</P>

    <dt id="42"></dt><h2>Recuperer la selection en cours dans une variable</h2>
    
<P>Pour recupérer la selection, on va utiliser une des rares commandes maya pourvues d'un nom peu instinctif. Il s'agit de la commande ls. Pas d'inquietude, cette commande est a la base de tellement de vos futurs scripts que vous allez bien vite la memoriser (en outre, les personnes familieres avec le noyau unix ne seront pas trop surprises… )
</P>

<P>Cette commande peut lister plusieurs choses en fonction des flags, mais ce qui nous interesse ici c'est la selection.
En se referant a la doc de maya, on constate donc que le flag qui nous interesse s'appelle -logiquement- selection (et on peut meme en trouver un exemple dans la doc maya, sur la page « ls » ) :
</P>

<?php addImage("12.png", "Un des exemples fournis par maya pour la commande ls")?>

<P>notre premiere ligne ressemble donc à ceci :</P>
<?php createCodeX("selectedObject = cmds.ls(selection = True)");?>

<P>Nous demandons ici a Maya de regarder ce qui est selectionne, et d'enregistrer le resultat dans une variable qui s'appelle selectedObject. Demandons a maya de nous imprimer le contenu de selectedObject pour voir le resultat (pensez evidemment a selectionner un objet avant...):</P>

<?php createCodeX("import maya.cmds as cmds
selectedObject = cmds.ls(selection=True)
print selectedObject");?>

<P>et le script editor nous renvoie :</P>

<?php addImage("13.png");?>

<P>Parfait, il nous renvoie bien le nom de l'objet selectionne (chez moi, une sphere qui s'appelle pSphere1) ! J'en profite pour m'arreter sur la notation : maya retourne [u'pSphere1'] avec des crochets, et non pSphere1, parce qu'il s'agit d'une liste : dans notre cas, il est evident que la liste n'etait pas necessaire, puisqu'on n'a selectionne qu'un element, mais ca, maya ne peut pas le savoir avant d'essayer. Donc par defaut, la commande 'ls' renvoie une liste. Comme indiqué dans la doc :
</P>

<?php addImage("14.png");?>

<P>string / crochet / crochet, ou, autrement dit, une liste de plusieurs strings (nous avons vu que [] etait la marque des listes). Ca peut paraitre anodin, mais pensez-y lorsque vous voudrez faire une operation qui n'est realisable que sur une string, et que maya vous dira que vous essayez de l'appliquer sur une liste ! Meme s'il n'y a qu'un seul element, c'est une liste ! Bref, pour l'instant, on est sur la bonne voie, on peut continuer !
Créons la variable qui nous servira de suffixe, pour ma part, ca va etre '_msh', pour mesh (modeling, quoi)
</P>

<?php createCodeX("import maya.cmds as cmds
selectedObject = cmds.ls(selection=True)
suffix = '_msh'");?>

<P>La encore, la colorisation syntaxique nous aide ; j'attire l'attention sur les guillements ! Pour l'instant, on ne sait pas encore trop quel type de variable il va nous falloir, etant donne que nous n'avons pas encore consulte la doc de la fonction 'rename', mais on peut imaginer qu'avec une variable string, on ne devrait pas etre completement dans le faux… et si jamais on se trompait, il suffirait de changer cette ligne, ou bien de convertir notre variable string en autre chose.
Tant qu'on y est, on peut aussi definir la variable newName, qui correspond au nom que l'on veut donner a notre objet :
</P>

<?php createCodeX("import maya.cmds as cmds

selectedObject = cmds.ls(selection=True)
suffix = '_msh'
newName = selectedObject + suffix");?>

<P>Le nouveau nom sera bien egal a l'ancien nom + le suffixe. Executons tout ca, pour verifier que tout fonctionne toujours.</P>

<?php addImage("15.png")?>

<P>Ah ! Une erreur ! Super ! J'ai conscience que quand on travaille sur un soft comme Maya, le plus souvent, le message d'erreur rouge qui peut apparaitre est synonyme de problemes... L'operation que vous souhaitez faire ne fonctionne pas. En script, c'est plutot l'inverse ! Vous allez apprendre a aimer les messages d'erreur ! En effet, quand un message d'erreur apparait, maya vous donne la solution a votre probleme. Si vous prenez le temps de lire, vous verrez qu'on vout dit assez precisement ce qui pose probleme. Voyons donc ca de plus pres :
</P>

<?php addImage("16.png")?>

<P>A partir de maintenant, d'ailleurs, je vous encourage a cocher en permanence la case "show stack trace" dans le script editor, sous le menu "History". L'erreur retournee par maya sera ainsi plus precise. Regardons donc ce que nous dit maya :</P>

<P>Si on traduit en francais, il nous dit qu'il a un probleme avec la ligne 5 (donc la ligne newName = selectedObject + suffix), et plus precisement, qu'il peut uniquement concatener (oui, <A HREF = "http://fr.wikipedia.org/wiki/Concaténation">ce mot existe aussi en francais =)</A>, c'est a dire 'assembler', 'additionner', ...,  une liste (pas une string) a une liste.
C'est la que le type des variables, dont nous avons parlé plus haut, prend toute son importance : En d'autres termes, un des elements qu'on met dans notre addition est une liste, tandis que l'autre est une string. Et c'est plutot logique que l'on puisse additionner uniquement des variables de meme type, si on y reflechit ! Quel serait le resultat de 33 + patate ? Ou bien de 3.14 + ['homer', 'bart', 'marge'] ? Le python ne sait pas le calculer non plus =)
</P>
<P>Alors, quel element est une liste, et quel element est une string ? Maintenant que le message d'erreur peut orienter vos recherches, le premier reflexe que vous devriez avoir est de tester le type de vos variables. Ca tombe bien, nous avons vu plus haut la fonction 'type' qui permettait de connaitre le type d'une variable. On peut l'utiliser ici :</P>

<?php createCodeX("selectedObject = cmds.ls(selection=True)
suffix = '_msh'

print type(selectedObject)
print type(suffix)

# newName = selectedObject + suffix");?>

<P>Vous notez que j'ai mis la derniere ligne (celle qui pose probleme) en commentaires, non pas pour m'aider a m'y retrouver, mais pour ne pas avoir a re-ecrire cette ligne une fois le probleme reglé. De cette maniere, python ne la lira plus, elle n'est visible que pour nous =)
Et le script editor nous renvoie :
</P>

<?php addImage("17.png")?>

<P>Effectivement, selectedObject est une liste, tandis que suffix est une string. Souvenez-vous, nous avons vu que cmds.ls() retournait une liste de strings, meme si un seul element etait selectionne ! 
Encore une fois, je ne rentrerai pas dans les details, mais sachez que vous pouvez isoler les elements d'une liste en indiquant entre crochets a la fin l'element que vous souhaitez. Si vous voulez recuperer le premier element d'une liste, vous ecrirez :
maListe[0]. Nous aborderons ca plus en detail dans le chapitre suivant, mais si vous brulez d'impatience d'en apprendre d'avantage sur les listes (et que vous ne savez pas scroller au chapitre d'en dessous =), le net regorge de tres bons tutos, dont les excellentes 'pythonneries', pour les francophones, que j'ai deja mentionnees ! Pour les listes plus precisement, n'importe quel site apparaissant sur la premiere page de google a la recherche "manipulation de listes python" devrait etre tres largement suffisant.
</P>



<P>Pour revenir a notre probleme, si nous voulons uniquement le premier element (et dans notre cas, l'unique) de la liste des elements selectionnes, il nous suffit de rajouter [0] a la fin de la ligne 3 :</P>

<?php createCodeX("selectedObject = cmds.ls(selection=True)[0]");?>
<P>Si vous executez a nouveau l'integralité du code (donc en 'de-commentant' la derniere ligne), vous constatez que nous n'avons plus d'erreur.</P>



<dt id="43"></dt><h2>La commande rename</h2>

<P>Maintenant, on arrive au plus gros, et a la nouveauté :  la commande rename.
Si je renomme ma sphere pSphere1 dans l'outliner, en pSphere2 par exemple, le script editor me renvoie :
</P>

<?php addImage("18.png")?>


<P>Maya utilise donc la commande 'rename'.
De base, si j'essaye de generaliser et convertir en python comme nous avons vu plus tot, ca devrait etre :
</P>

<?php createCodeX("cmds.rename('ancien nom', 'nouveau nom')");?>

<P>Voyons malgré tout ce que la doc nous dit… Au passage, je vous invite tres fortement a regarder le reste de la documentation : meme si vous ne comprenez pas tout, vous vous familiariserez avec la structure de l'aide, et petit a petit, vous comprendrez mieux telle ou telle partie. Pour ce qui nous interesse ici, on peut descendre directement aux examples :</P>

<?php addImage("19.png")?>

<P>On constate en tout cas que pour rename, la commande attend deux 'arguments', ou deux informations, si vous preferez :</P>

<OL>
    <LI>Le nom de l'objet qu'on veut renommer (d'ou l'EXTREME importance de ne pas avoir deux noms identiques dans un projet, sans quoi maya ne sait pas quel objet il doit selectionner, sans compter les problemes internes que ca peut poser a maya)</LI>
    <LI>Le nouveau nom, que l'on veut donner a l'objet designé par le premier argument ! Pour nous, ce sera donc l'ancien nom + le suffixe ! 
Le tout est separe par une virgule ! 
</LI>    
</OL>
<P>Facile, donc ! Ca donnera, pour nous :</P>

<?php createCodeX("# je renomme ('pSphere1' par 'pSphere1' + '_msh')
cmds.rename(selectedObject, selectedObject + suffix)");?>

<P>Bon, tout devrait rouler, il n'y a plus qu'a reselectionner notre sphere si toutefois elle etait deselectionnee, et lancer la totalite de notre code !
</P>

<?php addImage("20.png")?>

<P>Et voila le resultat ! Votre objet pSphere1 a ete renomme en pSphere1_msh !</P>

<P>Bien sur, dans cet exercice, l'intéret est limité, puisqu'on aurait pu renommer l'objet beaucoup plus vite avec des methodes 'classiques'. Nous allons donc voir comment rendre tout ca un peu plus utile =)
</P>


<dt id="50"></dt><h1>Troisieme partie : les listes et les boucles </h1>
<dt id="51"></dt><h2>Travailler avec les listes</h2>

<P>Il existe un type de variable un peu different que nous avons deja abordé, ce sont les listes. Si vous etes arrives jusqu'ici, vous devriez avoir deja compris que les listes sont en quelque sorte des series de variables.
Pour creer une liste, on l'a deja vu tout a l'heure :
maListe = []
Cette ligne cree une liste vide, la notation de la liste etant reconnaissable avec les deux crochets.
Tout l'interet, c'est que chaque objet d'une liste a un numero qui lui est associé. Ainsi, si</P>
        
<?php createCodeX("maListe = ['homer', 'marge', 'bart', 'maggie']");?>
<P>en imprimant maListe[0], je recupere le premier element de ma liste, qui est homer ! C'est ce que nous avons fait precedent pour recuperer le premier element de la liste renvoyee par cmds.ls(). Pour rappel, nous sommes en informatique, donc on commence a compter a partir de 0, le premier element de la liste etant donc l'element 0 ! Si je demande a python de m'imprimer maListe[10], il me retournera l'erreur 'list index out of range', qui explique qu'effectivement, le numero d'element de cette liste que je lui demande est en dehors du nombre total d'elements de cette liste.</P>
</P>
<P>L'interet ici n'est pas de proceder a l'elaboration d'une liste fastidieuse et complete des possibilites des listes,
je vous suggere plutot de voir ca au fur et a mesure de vos besoins. Je souhaiterai simplement que vous gardiez a l'esprit
que vous pouvez faire a peu pres tout ce que vous pouvez imaginer avec des listes :
<UL>
<LI>garder les x premiers elements d'une liste</LI>
<LI>garder les x derniers elements d'une liste</LI>
<LI>retirer les x premiers elements d'une liste</LI>
<LI>retirer les x derniers elements d'une liste</LI>
<LI>ajouter des elements a la liste (avec la fonction append)</LI>
<LI>retirer des elements d'une liste (avec la fonction remove)</LI>
<LI>etc etc</LI>
</UL>


<dt id="52"></dt><h2>Les boucles</h2>

<P>Attachez votre ceinture, on arrive au coeur du probleme ! L'interet le plus evident de scripter dans maya est l'automatisation. Imaginons par exemple que vous vouliez rajouter ce suffix '_msh' dont on parlait dans l'exercice 1 non pas a 1 element, mais a 400. La technique 'classique' qui consiste a renommer dans l'outliner risque de se reveler un peu decourageante. Imaginons maintenant qu'on puisse, en script, accomplir une ou plusieurs operations a la chaine, sur une multitude d'elements. C'est a ca que vont nous servir les boucles.
La encore, il existe plusieurs types de boucles, que nous n'allons pas voir en detail ici (une simple recherche « boucles python » sur google vous donnera toutes les infos que vous voulez), nous allons nous concentrer sur la boucle For, qui, de mon experience, est la plus utilisee pour maya.

<P>Voyons un peu maintenant la syntaxe : a nouveau, nous allons ecrire 'comme ca vient', de la maniere qui nous semble logique,
puis on 'corrigera' ensuite pour constater que les differences sont minimes. Je vous encourage a toujours proceder ainsi, c'est tres pratique !
C'est tellement pratique et courant que ca a meme un nom, le 'pseudo-code' =] Bref, allons-y :</P>
<?php createCodeX("Pour chaque element dans ma liste :
execute l'operation 1
execute l'operation 2
execute l'operation 3
sors de la boucle");?>

<P>Voyons a quoi ca ressemble en python :</P>

<?php createCodeX("for element in maListe:
    operation1
    operation2
    operation3");?>

<P>J'attire votre attention sur quelques points :

<OL>
    <LI>premierement, vous constatez qu'il s'agit quasiment d'une traduction litterale de ce que nous avions redige dans un premier temps en francais .. </LI>
    <LI>deuxiemement, la colorisation syntaxique nous aide une fois de plus, pensez a vous appuyer dessus.</LI>
    <LI>troisiemement, j'ai ici ecrit 'element', mais vous pourriez ecrire n'importe quoi d'autre. gardez juste a l'esprit que ce que vous ecrivez prendra la valeur, a chaque fois que python parcourera votre boucle, de l'element qu'il traite. Par exemple,
<?php createCodeX("for element in ['homer', 'bart', 'marge', 'maggie']:
    print element");?>

element vaudra d'abord 'homer', puis 'bart', puis 'marge', et enfin 'maggie'. Mais j'aurai tout aussi bien pu appeler mon element 'simpson', ainsi :
<?php createCodeX("for simpson in ['homer', 'bart', 'marge', 'maggie']");?></LI>
    <LI>quatriemement, nous n'avons precise a aucun moment que nous souhaitions sortir de la boucle apres l'operation 3. Si vous faites attention, vous constaterez que tout ce que je veux que ma boucle execute est aligne differemment, avec 4 espaces au debut. On dit que les instructions de ma boucle sont "indentees". L'indentation, c'est le fait de decaler a droite certains elements. Outre le fait qu'un texte bien indente est beaucoup plus lisible, l'indentation permet de hierarchiser les operations, et deux choses identiques avec une indentation differente peuvent dire completement autre chose. Par exemple

<?php createCodeX("for element in maListe:
    operation1
    operation2
    operation3");?>
est different de

<?php createCodeX("for element in maListe:
    operation1
operation2
operation3");?>

Dans le premier cas, mon script va appliquer l'operation 1, puis la 2, puis la 3, et ce sur chacun des elements de la liste. Dans le second cas, il va appliquer uniquement l'operation 1 sur chaque element de la liste, puis il appliquera l'operation 2 et l'operation 3 de maniere globale.
Concretement, ce dont on a besoin pour renommer une multitude d'objets en une operation, ce serait quelque chose qui ressemble a ca, en francais :
</P>
<?php createCodeX("selectedObjects = cmds.ls(selection=True)
suffix = '_msh' 

for element in selectedObjects:
    newName = element + suffix ");?>


<P>Voila, je sais que ca peux paraitre sommaire, mais avec ces tres simples notions, vous pouvez faire deja eeeeenormement dans Maya !
Alors pour les plus tatillons, je sais qu'on peut suffixer des elements en masse via la commande rename de maya, ou qu'on peut changer des attributs en masse
 via l'attribute spread sheet, mais croyez moi, ces quelques lignes vous servirons dans beaucoup de cas de figure ! </P>

<P>A partir de la, et dans l'idee de pratiquer un peu par soi-meme, je vous invite a manipuler un peu tout ce qui a ete vu la au travers de
divers exercices. Par exemple, quelque chose que vous ne pouvez pas faire autrement :
labellisez une serie de joints ! En partant du principe que tous les joints de votre scene obeissent a une naming convention claire,
essayez d'y associer automatiquement un label (super utile pour les mirror skins !)</P>

<P>
Pour vous aider un peu, je vous suggere quelques regles de base ainsi que quelques commandes utiles !

<OL>
	<LI>supposez que tous vos joints sont nommes avec la syntaxe side_nomDuJoint_JNT (par exemple, L_elbow_JNT pour le coude gauche)</LI>
	<LI>pensez a faire manuellement ce que vous voulez accomplir pour regarder ensuite dans le script editor ce que maya renvoie (comme setAttr pour setter un attribut....)</LI>
	<LI>vous aurez besoin de la methode 'split', qui permet de 'decouper' une string en une liste. On l'a deja vu plus haut, si besoin =]</LI>
	<LI>vous aurez egalement besoin de boucles de conditions, qui utilisent le mot cle 'if'. Google vous aidera la dessus !</LI>
</OL>
</P>

<P>En definitif, vous devriez pouvoir selectionner tous vos joints et lancer votre script pour recuperer tous vos joints labellises !
Voila, bon courage a vous, et n'hesitez pas a poster dans les commentaires si vous n'y arrivez pas !
A terme, maintenant qu'on a vu assez de theorie, j'aimerai pouvoir continuer ce cours sous forme d'exercices pratiques, donc n'hesitez pas non plus
a poster en commentaire les operations que vous souhaiteriez pouvoir faire en python ou des points plus specifiques que vous voudriez voir pour
qu'on les traite ici !
Bon courage !</P>