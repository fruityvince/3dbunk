<?php include_once 't/head.php';?>
<dt id="10"></dt><h1>Intro</h1>
<P>Il est assez frequent, dans maya, d'avoir besoin deux topologies qui matchent parfaitement, y compris en terme de numeros de vertices. Si vous souhaitez faire des blendshapes, ou automatiser telle ou telle action (en vous basant sur le fait que le vertex a telle position a systematiquement tel numero). Malheureusement, maya ne propose aucun outil pour le faire. Ou plus precisement, maya ne met pas en evidence l'outil pour le faire, car il existe bel et bien un tel outil, present depuis au moins maya 2010 je crois (peut-etre avant, mais j'ai commence la 3d sur 2010 ^^).</P>

<P>Nous allons donc voir un petit trick pour vous permettre de 'renumeroter' vos vertex sans toucher a votre topologie, en deux (... et quelques) etapes. Le core de cette technique reside dans l'utilisation d'un plug-in, livre avec maya et present dans votre installation. Nous allons donc devoir compiler ce plugin. Si vous voulez aller a l'essentiel, vous pouvez passer directement a la seconde partie et zapper la theorie</P>


<dt id="20"></dt><h1>Partie 1</h1>
<dt id="21"></dt><h2>Theorie</h2>
<P>Etant donne que le plugin n'est pas compile, il est necessaire de le <a href=https://fr.wikipedia.org/wiki/Compilateur>compiler</a> avant de l'utiliser. En d'autres termes, quand on execute du code avec Maya, celui-ci doit d'abord le 'traduire' dans un format que lui comprend, mais qui n'est pas lisible par un humain. Pour le mel, c'est un peu different, etant le langage natif de maya (mel = maya embeded language), mais le python, par exemple, n'est pas compris en l'etat par Maya, il est d'abord compile en pyc (python compile). Bien sur, tout ca se fait de maniere transparente pour le python, a la volee quand vous executez votre script. Mais pour du C++, il est necessaire de le compiler en amont (ce que vous faites si vous developpez deja des plugins avec l'API, mais dans ce cas, je ne vois pas ce que vous faites sur cette page =p). Bref, la partie la plus difficile sera la compilation.</P>

<P>Le souci avec la compilation, c'est qu'elle est dependante de votre environnement. Si vous etes sur mac os par exemple, vous aurez remarque que vos fichiers de plugin (les fichiers visibles dans le plugin manager) ont l'extension .bundle, tandis que sur linux vous avez des fichiers .so, et windows utilise des .mll. De meme, votre fichier sera dependant de votre version de maya (un fichier compile pour maya 2012 ne tournera pas sur maya 2016). En general, on peut compiler avec l'IDE qu'on utilise (si vous etes sur mac, xcode marche parfaitement, mais visual basic sur windows fait tres bien l'affaire. Je vous renvoie vers l'excellent travail de <a href=http://www.chadvernon.com/blog/>Chad Vernon</a> pour democratiser l'utilisation de l'API C++ de Maya, qui aborde entres autres la compilation). Tout l'interet de ce qu'on va voir ici reside dans le fait qu'on s'affranchit du setup tres complexe necessaire a la compilation. Et on peut faire cela grace a un fichier <I>MakeFile</I> fourni par Autodesk dans le repertoire des plugins, qui permet une compilation cross-plateforme. L'idee n'est pas ici de rentrer dans les details, mais justement de faire le strict minimum pour avoir quelque chose d'utilisable. Compte tenu du fait que chaque installation soit vraiment specifique a votre environnement (les elements deja installes, l'os, etc...), je vais faire de mon mieux pour vous donner une explication simple mais qui fonctionne neanmoins dans la plupart des cas.</P>

<dt id="22"></dt><h2>Preparation des fichiers</h2>
<P>Pour resumer, nous aurons besoin de 3 fichiers, ainsi que du projet. Le projet (donc l'ensemble des fichiers cpp/header) se trouve dans votre repertoire d'install de Maya, dans le dossier devkit/plug-ins. Je vous invite a y jeter un oeil si vous voulez commencer a faire vos propres plug-ins, vous trouverez ici plein de fichiers d'exemples d'utilisation de l'API ! Bref, donc pour moi, c'est :</P>
<?php createCodeX("/Applications/Autodesk/maya2015/devkit/plug-ins/meshReorder");?>
<P>Dans le meme dossier se trouver le fichier MakeFile, qui va se charger de donner toutes les infos necessaires au compileur.
Un dossier plus haut (donc dans la liste de tous les plugins mis a disposition par maya dans le devkit), vous trouverez les deux fichiers restants :
buildconfig et buildrules</P>

<P>Je m'arrete 5 minutes sur un point : histoire de ne pas corrompre les fichiers d'installation de maya, j'ai pour ma part choisi de copier/coller tout ce petit monde dans un dossier a part. Ainsi, quoi qu'on fasse, on ne modifie aucun fichier de l'installation de Maya, et si on veut recommencer avec des fichiers 'vierges' pour telle ou telle raison, il nous suffira de copier/coller a nouveau les fichiers depuis l'installation de maya. Il est toutefois <u>imperatif</u> de garder la meme structure de fichiers ! Voila a quoi ca ressemble de mon cote :</P>

<?php addImage("01.png", "Arborescence du 'projet'");?>

<P>Nous n'allons toucher ni a MakeFile ni buildrules, qui sont deja corrects, merci Autodesk. En revanche, il va falloir editer buildconfig, pour lui indiquer le dossier dans lequel maya a ete installe. Ouvrez donc buildconfig avec n'importe quel editeur de texte. Aux alentours de la ligne 35 (c'est a la ligne 37 pour moi, precisement), vous trouverez une ligne qui ressemble a ca :</P>
<?php createCodeX("ifeq ($(MAYA_LOCATION),)
    MAYA_LOCATION = /Applications/Autodesk/maya$(mayaVersion)/Maya.app/Contents
");?>
<P>Vous l'aurez compris, il s'agit ici de remplacer le chemin fourni ici (qui sera different en fonction de votre OS) par le reel chemin d'install. Donc chez moi, ca donne :</P>
<?php createCodeX("ifeq ($(MAYA_LOCATION),)
    MAYA_LOCATION = /Applications/Autodesk/maya2015/Maya.app/Contents
");?>
    
<P>Bien sur, si vous etes sur windows ou linux, ce chemin sera tres probablement different.</P>

<P>Une fois ce fichier modifie, vous pouvez sauvegarder et fermer.</P>


<dt id="30"></dt><h1>Partie 2</h1>
<dt id="31"></dt><h2>Compilation du code</h2>

<?php
addTip("Avant de lancer la compilation, il est important de preciser que pour compiler, vous avez en general besoin de certaines librairies. Pour eviter de partir dans un setup beaucoup trop complexe, le mieux est sans doute d'installer visual studio (windows), xcode (macOs), n'importe quel ide linux, de sorte a beneficier de l'install automatique des librairies livrees avec. Petite subtilite pour macOS, qui a besoin du sdk 10.8. Dans les grandes lignes, disons que la compilation des plugins maya se fait en utilisant le sdk 10.8, qui ne se trouve plus tres facilement. Deux solutions, donc :
La premiere consiste, quand vous developpez directement dans xCode, a choisir le sdk 10.9 malgre les recommandations d'autodesk (je n'ai encore jamais rencontre de probleme).
Quant a la seconde, au moins aussi punk, elle consiste a re-creer un sdk 10.8 : si vous avez installe xCode, vous devriez avoir deja macOS 10.9 et 10.10 sdk, localises tous les deux dans /Applications/Xcode.app/Contents/Developer/Platforms/MacOSX.platform/Developer/SDKs. Si vous ne voulez pas avoir a partir a la chasse aux packages macOS, vous pouvez copier/coller sauvagement le 10.9 et le renommer en 10.8 =]");?>


<P>Pour la suite, il nous faut passer sur le terminal pour mac ou linux, ou la console pour windows.
De la, naviguez jusqu'a l'interieur du repertoire meshReorder, en utilisant la commande cd (pour change directory). Je n'ai plus utilise windows depuis un bail, donc je ne me souviens plus la commande pour changer de dossier, mais de memoire ca doit etre soit cd, soit simplement le chemin. Une rapide recherche sur google devrait vous en dire plus.
Bref, allons-y :</P>

<?php addImage("02.png", "Naviguez jusqu'au dossier qui contient votre projet");?>

<P>Une fois ici, tout ce qu'il nous reste a faire, c'est de lancer la commande 'make'. Ca aura pour effet de lancer la compilation.</P>

<?php addImage("03.png", "Tadaaaam");?>

<?php
addTip("Si toutefois vous avez un probleme de 'linker' (lisez ce qu'output la console), c'est tres probablement du au fait que vous avez mal renseigne le chemin vers maya, et que la compilation ne peut pas trouver des fichiers necessaires contenus dans le dossier maya.");?>

<P>Voila, vous devriez maintenant avoir un fichier .mll / .so / .bundle en fonction de votre OS, reconnu par maya. Vous pouvez donc maintenant ouvrir maya et vous rendre dans le plug-in manager, puis 'browser' jusqu'a l'endroit ou vous avez compile votre plugin pour le selectionner puis le loader.</P>

<?php addImage("04.png", "");?>
<?php addImage("05.png", "Notre plug-in est bien reconnu par maya");?>


<dt id="32"></dt><h2>Utilisation du plug-in</h2>

<P>Toutefois, ce plug-in est une commande maya. Il vous faut donc appeler cette commande depuis maya en fournissant les bons arguments.</P>

<P>Pour utiliser notre commande, on peut voir dans la partie 'infos' du plug-in manager que la commande qui nous interesse est 'meshReorder'. Elle attend 3 arguments, qui sont les trois vertices d'une meme face, et qui deviendront les nouveaux trois premiers vertices. Bien sur, il faut qu'ils appartiennent a la meme face... Donc la syntaxe, dans un onglet MEL, ressemblera a ca :</P>
<?php createCodeX("meshReorder mesh.vtx[23] mesh.vtx[12] mesh.vtx[341];");?>

<P>Comme avec n'importe quel plug-in (et tout specialement un plug-in developpe par Autodesk =p), sauvegardez avant de l'utiliser ; en cas de probleme, et si l'erreur n'a pas ete geree dans le plug-in, maya crash, sans preavis. Donc si vous ne fournissez pas exactement le bon nombre d'arguments, si vous vous trompez dans la nature des arguments fournis, ou quoique ce soit que le plug-in n'est pas pret a recevoir, ca crashera. En outre, je crois savoir que les meshs tres tres lourds posent probleme, meme si je n'en ai jamais fait l'experience personnelement. Chad Vernon a ecrit une nouvelle version, plus rapide, et avec une bien meilleure gestion des erreurs, et l'a envoye a autodesk il y a quelques mois / annees, donc Autodesk devrait remplacer la version actuelle, inch'allah =]</P>

<P>Pour les personnes les moins a l'aises avec le script, vous pouvez trouver assez facilement sur internet une interface pour ne pas avoir a ecrire la commande manuellement, et pour les personnes qui ne sont a l'aise ni avec le script, ni avec google, je vous ai bricole une petite ui exclusivite 3dbunk qui fait le job pour vous ^__^ Rendez vous dans la section telechargement pour telecharger le fichier en question. Utilisant PyQt ou PySide, vous ne pouvez l'utiliser qu'avec maya 2013 au minimum, ou une version precedente si tant est que vous ayez installe pyqt4. De plus, il faut evidemment que votre plug-in se trouve dans un des repertoires reconnus par maya, ou bien que vous le loadiez manuellement via le plug-in manager. Enfin, n'hesitez pas a me faire savoir si ca ne fonctionne pas comme ca devrait, l'ecriture s'est fait assez rapidement et la phase de tests encore plus...</P>


<dt id="40"></dt><h1>Conclusion</h1>
<P>Comme dit precedent, du fait de la nature sensible de l'operation, il se peut que vous ayez des difficultes que je ne mentionne pas ici. Si tel est le cas, n'hesitez pas a poster en commentaire, et j'essayerai de repondre</P>

<P>Il me parait egalement utile de noter que les plug-ins presents dans devkit sont - a ma connaissance - disponibles a des fins d'exemple et d'apprentissage, et ne devraient pas etre utilises en production sauf absolue necessite. Ce qui rend l'utilisation de meshReorder moins critique, c'est qu'il ne laisse aucune trace dans votre scene. Il ne s'agit pas d'un node, mais d'une commande, utilisee a un instant t et qui disparait ensuite. Je vous invite toutefois a prendre toutes vos precautions avant l'utilisation : mon binome de 3dbunk et moi-meme l'avons deja utilise en production sur un long metrage et nous n'avons reference aucun probleme, mais on ne sait jamais !</P>

