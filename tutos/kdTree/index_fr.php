<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>(Longue) introduction</h1>
<p>Je me suis recemment retrouve dans une situation ou je devais copier le skin d'un objet sur un autre objet.
La situation classique, c'est le cas 'vetements sur peau'. </p>

<?php addImage("01.gif", "Comme d'hab, la mod n'est pas de moi, mais de <a href='https://www.artstation.com/artist/g00girl'>Claire Blustin</a>, meilleure modeleuse du monde !");?>

<p>Etant donne que j'avais deja skinne certaines parties de la peau, je n'avais pas envie de perdre ce que j'avais deja en copiant le vetement par dessus. Le copy skin de maya a beau fonctionner en mode component, je me voyais assez mal selectionner tous les vertices qui m'interessaient a la main (surtout en tout debut de prod', avec la perspective d'avoir a refaire l'operation un certain nombre de fois !). </p>
<p>Bref, je me suis donc mis en tete de me faire un petit tool pour selectionner automatiquement les vertices de la peau situes a moins de x unites de distance du point le plus proche du vetement. Et puis, ca pourra toujours servir.</p>

<p>Impec, maya fournit les tools pour le faire ! Un petit coup de getClosestPoint(), et ca devrait etre regle, a moi la glande sur <a href="https://www.youtube.com/watch?v=x537Cqg5nEI">youtube</a> au lieu de selectionner mes points manuellement ! </p>

<?php createCodeX("
from maya.OpenMaya import *
from fn import fApi
def getClosestVertices(src, dst, tol=0.1):
    ''' 
    With a given source mesh and destination mesh, 
    will select all the vertices of the destination mesh that are 
    at a given distance of the closest point on the source mesh 
    (the given distance being 'tol' argument)
    '''   
    mSrc = fApi.nameToMObject(src)[0] # just returns an MObject, based on the given string
    mDst = fApi.nameToMObject(dst)[0] # just returns an MObject, based on the given string
    # i get a dagPath because if we give just the mobject to the fn set or the iterator, 
    # obviously, we can't work in world space.
    dagSrc = MDagPath()
    dagDst = MDagPath()
    MDagPath.getAPathTo(mSrc, dagSrc)
    MDagPath.getAPathTo(mDst, dagDst)

    fnSrc = MFnMesh(dagSrc)
    it = MItMeshVertex(dagDst)
    
    includedVertices = []
    while not it.isDone():
        closestPoint = MPoint()
        currPoint = it.position(MSpace.kWorld)
        fnSrc.getClosestPoint(currPoint, closestPoint, MSpace.kWorld) 
        v = currPoint - closestPoint
        if v.length() < tol:
            includedVertices.append(it.index())
        it.next()
    return includedVertices
");
?>
<p>Un peu crados, mais ca devrait faire le job ! Je cleanerai plus tard !</p>

<p>Je lance mon truc, et... crash de maya ? Non, le resultat souhaite, mais plus de VINGT fucking secondes de process !</p>
<?php addImage("01b.gif", "Ca marche, mais c'est loooong");?>


<p>La patience n'a jamais ete mon fort, pas moyen de garder un truc aussi long.
Et en meme temps, le coupable est tout designe. Comme a chaque fois qu'on fait appel a des commandes maya, on utilise un wrapper (dans le cas de l'api, un wrapper python de l'api c++ qui est elle meme un wrapper du core. Et qui sait l'algorithme qu'utilise le core...).</p>
<p>Je me souviens alors d'une discussion avec mon ancien HOD, a.k.a. le puits de savoir (coucou Matt =), a propos des 'kd-tree', utilises pour optimiser drastiquement les calculs de proximite dans un espace en n-dimensions. Il est temps dinvestigate un peu plus la dessus !</p>

<p>Le domaine etant visiblement tres vaste (et tout nouveau pour moi ^^), je n'aborderai ici que la partie theorique, et de maniere assez superficielle. Pour une implementation python, je peux partager la mienne si vous le souhaitez (commentaires, toussa...) ou une rapide recherche google vous amenera sur <a href="https://github.com/stefankoegl/kdtree">le github de quelqu'un qui l'a fait</a>. Je ne l'ai toutefois pas teste. Enfin, <a href="https://docs.scipy.org/doc/scipy-0.14.0/reference/generated/scipy.spatial.KDTree.html">numpy/scipy</a> proposent une implementation egalement, je crois.</p>


<dt id="20"></dt><h1>Qu'est-ce qu'un k-d tree</h1>
<dt id="21"></dt><h2>Definition et signification</h2>

<p>Commme d'habitude, derriere ce nom un peu austere se cache un concept finalement pas si complique que ca. Bien sur, <href a='https://en.wikipedia.org/wiki/K-d_tree'>l'article wikipedia</a> sur le sujet reste assez hermetique ! Mais on comprend que le 'k' du kd tree correspond au nombre de dimensions. Donc technique habituelle, j'essaye de simplifier au maximum le truc pour comprendre le concept de base. On va donc partir sur un 2-d Tree</p>

<p>Avant d'entrer dans le detail, je vous propose donc une definition, largement incomplete, possiblement remplie d'approximations, mais qui a le merite d'expliquer avec des mots simples de quoi il s'agit ! </p>

<?php addNote("
Un k-d Tree est une maniere optimisee de ranger (et donc, a terme, de parcourir) un ensemble de donnees. En d'autres termes, si je construis un arbre a partir d'un ensemble de points (au hasard, tous les vertices d'un mesh), je devrai etre a meme de retrouver beaucoup plus rapidement le point le plus proche d'un nouveau point donne. Pile ce qu'on cherche, donc ! Le kd-tree est juste une maniere de classer, pour pouvoir retrouver plus facilement, toutes les coordonnees de mes vertices. 
    J'insiste d'ailleurs sur ce point, le kd tree effectue un nombre mineur d'operations mathematiques, se contentant de les garder en memoire des la premiere iteration. Le noyau du systeme reside dans l'organisation des datas. Dans le cas qui nous interesse, evidemment, on est sur une base 3D (on pourrait donc appeler ca un 3D-tree), mais le concept fonctionne avec autant de dimensions qu'on le souhaite.
")?>


<dt id="22"></dt><h2>Mise en pratique avec un 2-d tree</h2>

<p>Voyons maintenant la structure d'un k-d tree de plus pres ! Bien sur, on va d'abord fonctionner en 2D pour plus de simplicite !</p>

<p>Imaginons la serie - completement arbitraire - de coordonnees suivante : 
<ul>
<li>A[5, 4]</li>
<li>B[2, 9]</li>
<li>C[3, 5]</li>
<li>D[2, 2]</li>
<li>E[9, 2]</li>
<li>F[6, 1]</li>
<li>G[9, 9]</li>
</ul>

<p>On va generer, a partir de ca, un kd-tree, et placer les elements sur la grille en meme temps !
Placons deja A. Rien de bien complique : </p>

<?php addImage("02.jpg", "Et voila notre point A(x=5, y=4)");?>

<p>J'en profite pour rajouter A en bas de ma grille... Cette premiere 'brique' en dessous de ma grille sera le node 'root' de mon arbre ! Ensuite, ca se passe comme un arbre genealogique ou comme n'importe quel arbre hierarchique ! Chaque node a des enfants, qui eux-memes ont des enfants (c'est degueulasse !), et ainsi de suite. Dans le cas d'un kd-tree, il y a systematiquement deux enfants, un a droite, et un a gauche. Maintenant, comment determiner si un enfant doit aller a droite ou a gauche ? En comparant tout simplement les valeurs de position, dimension par dimension.</p>

<p><u>Pour placer B</u>, nous allons donc comparer sa valeur en x avec la valeur x de A :<br></p>

    <ul>
        <li>B.x < A.x</li>
        puisque 
        <li>2 < 5</li>
    </ul>

<p>Notre B prendra donc place a gauche du A dans l'arbre (et sur la grille, puisqu'on s'interessait a l'axe X, en l'occurrence) !</p>

<?php addImage("03.jpg", "B prend place, sur l'arbre, a gauche de A");?>

<p><u>Passons maintenant au C[3, 5]</u>.
    <ul>
        <li>On compare d'abord la valeur C.x (3) a A.x (5), pour deduire que C doit partir vers la gauche.</li>
        <li>Puis, on compare C.y a B.y. dans ce cas, C.y est plus petit que B.y, notre C ira donc a gauche de B.</li>
    </ul>
</p>
<?php addImage("04.jpg", "Sans surprise, voila notre C...");?>

<p><u>On continue avec D[2, 2].</u>
    <ul>
        <li>D.x compare a A.x -> 2 compare a 5 : on part a gauche</li>
        <li>D.y compare a B.y -> 2 compare a 9 : on part a gauche</li>
        <li>D.x compare a C.x -> 2 compare a 3 : on part a gauche</li>
    </ul>
<?php addTip("Ici, un petit piege... Les deux premieres comparaisons sont faciles, on compare avec x, puis y. Pour la troisieme comparaison, on revient tout simplement a x. Et en 3d, c'est la meme chose, avec une dimension en plus. On compare a x, puis y, puis z, puis on revient a x, et ainsi de suite");?>

<?php addImage("05.jpg", "Pour l'instant, je vous l'accorde, notre arbre ne ressemble pas trop a un arbre, plus a un baton ! Mais patience... ");?>


<p>Et ainsi de suite, pour arriver au resultat final. Je vous passe les details, ca devrait donner quelque chose de cet ordre :</p>

<?php addImage("06.jpg", "Voila, ca a un peu plus une gueule d'arbre ! Imaginez maintenant la tete du truc pour un nuage de points ! ");?>


<p>Et voila, notre arbre est pret ! </p>

<p>L'element <b><u>CLE</b></u> a comprendre, c'est bien evidemment le fait qu'on alterne les axes a chaque comparaison, et on ne travaille a chaque fois que sur un axe ! 
Pour bien comprendre ca, et avoir un peu plus conscience de ce qu'on a fait, dans l'espace 3D (enfin en l'occurrence, 2D, dans notre cas), dessinons quelques reperes supplementaires !</p>

<p>En commencant avec A, ce qu'on fait en realite, c'est qu'on place ses enfants en fonction de leur valeur en X. En d'autres termes, on scinde notre grille en 2 dans l'axe perpendiculaire a X (encore une fois, en 2D, c'est facile, c'est forcement l' "autre" axe, ici Y). Si la valeur X de l'enfant est plus petite, l'enfant est a gauche, sinon il est a droite : </p>

<?php addImage("07.jpg", "On coupe litteralement l'espace de travail en deux");?>

<?php addTip("Travaillant en 2D, on scinde la grille avec un element en 1 dimension (une ligne), mais evidemment, dans des exemples en n dimension, on scindera avec un element en n - 1 dimension (on scinde avec un plan 2D, dans le cas d'une scene 3D, pour prendre un exemple parlant =) ");?>


<p>Puis, on re-scinde la grille en 2, mais cette fois dans le sens horizontal (perpendiculaire a Y), dans l'une ou l'autre des moities, en se basant evidemment sur le point suivant.</p>

<?php addImage("08.jpg", "");?>

<p>En procedant ainsi de maniere recursive, on arrive a un espace completement separe et hierarchise, dans lequel il devient beaucoup plus rapide de naviguer (en simplifiant enormement : vous comparez votre premier point dans 1 dimension, et vous pouvez directement eliminer quasiment la moitie des points, vous savez que le closest point ne se trouvera pas la ! Et vous decimez ainsi les elements du tableau beaucoup plus rapidement !)</p>

<?php addImage("09.jpg", "");?>


<dt id="30"></dt><h1>Utilisation d'un k-d tree en 3 dimensions</h1>
<dt id="31"></dt><h2>Implementation possible en k dimensions</h2>

<p>Je vous propose ici une implementation possible d'une methode pour gerer un kd tree.
Dans le but de rester le plus simple possible, j'ai retire une bonne partie de ce qui serait necessaire pour l'implementation d'un code robuste. Ainsi, Cette methode seule ne sert a rien, prenez la plus comme du pseudo-code que comme une fonction prete a l'emploi. Pour quelque chose deja utilisable (mais beaucoup plus long et complexe), je vous redirige vers le github cite en introduction.

<?php createCodeX("
def buildTree(datas, depth=0):
    ''' Builds a tree made of KdNodes. The tree is ran recursively '''
    # in order to swich axis at each iteration, we get the modulo of 
    # depth % number of dimensions, so axis will be 0%3=0 (x), 
    # 1%3(y), 2%3(z), and will come back to 0 (3%3), 1 (4%3), and so on)
    axis = depth % dimensions

    # then, we'll sort our list of arrays, based on the dimension we want
    # for example, if we sort A(1, 4), B(2, 2) and C(5, 1) by y 
    # (using key=lambda pt: pt[1]), we'll get C, B, A
    datas.sort(key=lambda pt: pt[axis])
    # the mid point for this dimension will be the middle-th element 
    # of the list (we use // because we want an index (i.e. int), not 
    # a float, obviously)
    midPointIdx = len(datas)//2

    # so all we have to do is to split this list, create a node from 
    # the current mid value (the Node class can store the left and 
    # right child, for example), 
    # and run buildTree again with the remaining 2 arrays.
    currentPoint = datas[midPointIdx][0]
    left = buildTree(datas[:midPointIdx], depth+1)
    right = buildTree(datas[midPointIdx+1:], depth+1)
    currentNode = KdTree.KdNode(currentPoint, left, right)
    
    return currentNode
");?>

<dt id="32"></dt><h2>Interpreter ces valeurs</h2>


<p>D'une certaine maniere, on pourrait comparer ce processus a n'importe quel autre module de compression / decompression dont on est familiers, comme cPickle ou json, frequemment utilises dans maya. En creant l'arbre, on json.dump() notre point array. Mais pour en tirer avantage, il faudra le json.load(), c'est a dire le 'decoder' de sorte a tirer avantage de sa structure.</p>

<p>Malheureusement, on rentre dans un quelque chose de bien plus complexe, qui est <a href="https://en.wikipedia.org/wiki/K-nearest_neighbors_algorithm">un domaine d'expertise a part entiere</a>, et un enjeu important de l'imagerie numerique actuelle, de ce que j'ai pu en comprendre, dans la mesure ou, comme pour la plupart des algorithmes bas niveau (comme la decomposition lower upper, ou meme pire, la boolean algebra (je vous recommande ce <a href="http://www.nand2tetris.org/book.php">genial bouquin</a>, si le sujet vous interesse!)), c'est a la base de calculs massifs (encore une fois, imaginez a quoi peut ressembler un arbre de 20 000 points !), et ca ouvre donc la porte a beaucoup de tools plus funs les uns que les autres (qui a dit 'proximity map' ?)</p>

<p>Je ne m'etendrai donc pas sur le sujet, deja parce que je suis tres loin de le maitriser, et ensuite parce que je ne saurai meme pas par ou commencer. Les quelques jours de recherche que j'ai fait jusqu'a maintenant me montrent beaucoup d'implementations differentes, et je suis bien loin d'avoir le temps de benchmarker tout ca ! </p>

<p>Mais pourquoi est-ce aussi efficace ? L'idee, comme on l'a dit, c'est qu'on peut isoler tres rapidement l'element le plus proche en utilisant une logique booleenne. Considerant la hierarchie de l'arbre, et avec un point P donne, on va comparer P.x au root.x. Si le premier est plus grand que le second, on cherchera notre closest point dans la partie de droite, sinon dans la partie de gauche. C'est 0 ou 1, vrai ou faux, gauche ou droite. Meme chose avec le niveau suivant, et ainsi de suite. La limitation, bine sur, c'est que contrairement au getClosestPoint(), on obtient a terme le closest vertex, pas le closest point sur le mesh. Donc plus le mesh est lourd, plus la marge d'erreur sera minime, et plus le gain de temps sera consequent, compare a un getClosestPoint. Mais dans le cas d'un mesh super low poly, bien sur, il sera probablement plus efficace d'utiliser le getClosestPoint, d'autant que le temps ne sera pas un probleme</p>

<p>Je vous recommande, si vous voulez aller plus loin, d'aller jeter un oeil du cote du siggraph, et des papiers plus techniques. Je me suis pour ma part base sur <a href="http://pubs.cs.uct.ac.za/archive/00000847/01/kd-backtrack-techrep.pdf">celui-ci</a> pour mon implementation, plus par facilite que par choix reel (les mecs parlent de computer science, je me dis que c'est similaire a mes besoins). Mais je crois savoir que differentes implementations existent. Si vraiment ca interesse du monde, n'hesitez pas a me le faire savoir en commentaires, et je ferai en sorte de vulgariser le principe, au moins pour l'implementation que j'ai choisi !</p>

<p>L'interet, a terme, reste de pouvoir query tres rapidement le point le plus proche, et donc l'index du vertex le plus proche, la distance qui separe le query point de ce point le plus proche, la position exacte du vertex dans l'espace, etc... Etant donne qu'il n'y a aucun calcul, mais qu'il s'agit exclusivement d'organisation de grosses arrays, pour simplifier, vous pouvez combiner l'info que vous voulez a votre array. La seule operation mathematique, c'est un bete calcul de distance entre 2 points.</p>


<dt id="40"></dt><h1>Conclusion</h1>

<p>Tout ca nous amene bien loin du probleme initial, mais pour vous motiver a vous pencher sur les kd trees, j'ai remplace l'appel a getClosestPoint() par mon implementation du kd-tree, et je passe de 8.62844705582s a 1.25752019882, soit une petite amelieration de plus de 600%, biiiim !
Et ce n'est que du python, et je suis sur que je peux encore l'optimiser ! Imaginez la version c+ optimisee de ce mecanisme ! </p>

<p>J'espere que cette petite introduction aux kd-trees vous aura donne envie de vous pencher sur le sujet ! Si vous voulez en savoir davantage (en particulier sur la maniere de recuperer les resultats), n'hesitez pas a le faire savoir en commentaire. Pour ma part, je dois vous avouer que ca me donne pas mal d'idees ! Il n'y a plus qu'a se mettre au boulot !</p>

<?php addVideo("190039595", $type="vimeo", $width=640, $height=400);?>