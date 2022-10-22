<?php include_once 't/head.php';?>

<dt id="10"></dt><h1>(Long) introduction</h1>
<p>Recently, I had to copy the skin weights of an object on another object. The typical exemple is when you have some cloth on a skin and you want to copy the cloth weights on the skin.</p>

<?php addImage("01.gif", "As usual, the modeling is not from me, but from Claire Blustin, best modeler in the world !");?>

<p>Considering the fact I skinned some parts of the skin already (the fingers), I didn't want to loose what I had already by copying the cloth on top of it. I know maya's native copy skin can work on components, i didn't want to select manually all the vertices i wanted to target (especially at the very beginning of a production, knowing that i'd have to do it again and again !).</p>
<p>Anyway... so I wanted to do a little tool to select automatically all the vertices on the skin that are at less than x units of distance from the closest point on the cloth. Plus it'll always be useful for something else...</p>

<p>Maya gives us the tools to do it already ! Let's use some getClosestPoint(), and it should do the trick and let me some time to chill on <a href="https://www.youtube.com/watch?v=x537Cqg5nEI">youtube</a> instead of painfully select all my vertices manually !</p>

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
    mSrc = fApi.nameToMObject(src)[0] # just returns an MObject, based on the given string
    mDst = fApi.nameToMObject(dst)[0] # just returns an MObject, based on the given string
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
");?>
<p>Not really clean, but that'll do the job. I'll do a pass of cleaning later !</p>

<p>So here we go ! I run my code, and... maya crash ? No, the expected result, but it took more than 20 fucking seconds to process !</p>
<?php addImage("01b.gif", "It works, but it's sooooooo long !");?>

<p>Patience has never been my best quality, no way to keep something so long. And at this point, I already know what's causing the process to be so heavy ! Like every time we use maya commands over pure code, we use a wrapper (and in this case, a python wrapper of a c++ wrapper of the core of maya ! And who knows which algorithm the core is using...).</p>
<p>Then I remembered a discussion I had with my former HOD, a.k.a. the Source of Knowledge (hello Matt =), about something called 'kd-trees', used to optimize drastically proximity operations in an n-dimentional space. Time to investigate a bit further !</p>

<p>As it seems to be a huge field of research (and brand new to me !), I will focus here on the theory, and won't go into details. For a python implementation, i can share mine if you want (just ask in the comments), or a quick google search will bring you on <a href="https://github.com/stefankoegl/kdtree">the repo of someone who did it already</a>. I didn't test this out, though. Finally, <a href="https://docs.scipy.org/doc/scipy-0.14.0/reference/generated/scipy.spatial.KDTree.html">numpy/scipy</a> come with some tools to do it too, i think. But my point is that you won't find here every bits and bobs that you can just copy/paste to have something working.</p>


<dt id="20"></dt><h1>What is a k-d tree</h1>
<dt id="21"></dt><h2>Definition and meaning</h2>

<p>As usual, behind this mysterious name, the idea is not that difficult. Of course, <href a='https://en.wikipedia.org/wiki/K-d_tree'>the wiki page</a> about it remains a bit blury ! But we understand that the 'k' of k-d tree corresonds to the number of dimensions. So usual workflow, I try to simplify as much as possible the method, to extract the low level concept. Let's work with a 2-d tree !</p>

<p>Before going into too much detail, I can submit my own definition, probably not super accurate, but at least it uses simple words !</p>

<?php addNote("
A k-d Tree is an optimised way of sorting (and ultimately, scan) a set of datas. In other words, if I build a tree from a set of points (for example, every vertex of a mesh !), I should be able to retreive much faster the closest point from a new given point.
Exactly what we're looking for ! I'll strongly insist on the fact that the k-d tree does not do any calculation (almost !), and all the operations it'll perform will be kept into memory so that we don't have to do them again. The core of the system consists in the structure of the datas. For us, obviously, most of the time we'll work on a 3D basis (so we could call it a 3-d tree), but the idea works with as many dimensions as you wish.
")?>


<dt id="22"></dt><h2>Example, using a 2-d tree</h2>

<p>Let's see now the structure of k-d tree in detail ! We'll work with only 2 dimensions at first, to keep things simple.</p>

<p>Let's assume the following set of coords :
<ul>
<li>A[5, 4]</li>
<li>B[2, 9]</li>
<li>C[3, 5]</li>
<li>D[2, 2]</li>
<li>E[9, 2]</li>
<li>F[6, 1]</li>
<li>G[9, 9]</li>
</ul>

<p>We'll generate, from this set, a k-d tree, and put those points on a 2d grid at the same time. Let's start with A. Nothing crazy here : </p>

<?php addImage("02.jpg", "Here is our A(x=5, y=4) point");?>

<p>While doing that, let's also add A at the bottom, under the grid. This first 'node', below the grid, will be our 'root' node for this tree. Then, we populate information just like any type of tree. Each node has children, each child has other children, and so on and so forth ! In the case of a k-d tree, there are always 2 children, one on the left, one on the right. Now, how to define if a child should take place on the right or the left ? By comparing the position values, dimension by dimension.</p>

<p><u>To set B</u>, we'll compare his x value with the x value of A, like so :<br></p>

    <ul>
        <li>B.x < A.x</li>
        because
        <li>2 < 5</li>
    </ul>

<p>So our B point will take place on the left of A, in the tree (and on the grid as well, obviously, as we were comparing the 'x' value. But I do hope that you know how to put a point on a grid if you have its coords. ^^) !</p>

<?php addImage("03.jpg", "B takes place, on the tree, on the left of A");?>

<p><u>Let's move on to C[3, 5]</u>.
    <ul>
        <li>We'll compare first the value C.x (3) against A.x (5), to deduce that C will go to the left.</li>
        <li>Then we'll compare C.y to B.y. In this case, C.y is smaller than B.y, therefore our C will go to the left of B.</li>
    </ul>
</p>
<?php addImage("04.jpg", "Forget about the grid, focus just on the tree and on how to populate new elements, following the logic we're seeing");?>

<p><u>Now D[2, 2].</u>
    <ul>
        <li>D.x compared to A.x   ->   2 compared to 5 : left</li>
        <li>D.y compared to B.y   ->   2 compared to 9 : left again...</li>
        <li>D.x compared to C.x   ->   2 compared to 3 : and left again !</li>
    </ul>
<?php addTip("Here, a little trap... The 2 first comparisons are easy, we compare with the x value, then with the y value. For the 3rd one, we just go back to the x value. And in 3d, it's the same thing, with one extra dimension. We compare to x, then y, then z, then we come back to x, and so on.");?>

<?php addImage("05.jpg", "For now, i agree, our tree doesn't really look like a tree, a  stick, at most ! But be patient... ");?>


<p>And let's continue with the same logic to end up with this result :</p>

<?php addImage("06.jpg", "Voila, it looks a tiny bit more like a tree ! Now imagine how it could look like with a 10 000 point cloud ^^");?>


<p>Done, our tree is ready !</p>

<p>The <b><u>KEY</b></u> thing to understand is that we swap axis between each comparison, and we work every time on 1 and only 1 axis ! To understand better the consequences of this workflow in a 3d space (or in a 2d space, in our case), let's draw some extra markers !</p>

<p>Starting with A, what we actually do is that we put its children regarding their X value. In other words, we split our grid into 2 parts, in perpendiculary axis (once again, in 2d, it's easy, it has to be the 'other' axis, Y here). If the x value of the child is smaller than its parent's x value, the child goes to the left, otherwise it goes to the right :</p>

<?php addImage("07.jpg", "We litteraly split the work space into 2 parts");?>

<?php addTip("Working in 2d, we split the grid with a 1-dimension element (a line), but obviously, in an n-dimensionnal example, we would split with an n - 1 dimension element (if we work in a 3D scene, we'd split with a (3 - 1)D element, a.k.a a 'plane', in 2d =)");?>

<p>Then, we split again the grid in 2, but this time, horizontally (i.e. perpendicular to Y axis), in one and the other half, based on the next point.</p>

<?php addImage("08.jpg", "");?>

<p>By doing that recursively, we end up with a super split space, in which it becomes much faster and easier to navigate. If we simplify the idea : you compare your first point only in 1 dimension instead of 3, and from that result, you can already eliminate half of the points ! You know the closest point won't be here ! So you decimate elements of the graph much faster, by removing half of what remains on every iteration !)</p>

<?php addImage("09.jpg", "");?>


<dt id="30"></dt><h1>Using a k-d tree in 3 dimensions</h1>
<dt id="31"></dt><h2>Possible k-dimensions implementation</h2>

<p>What I submit here is a possible implementation of a method to generate a k-d tree. In order to keep things as simple as possible, i removed a huge part of what would be necessary to have a robust code. Therefore, this method on its own is not usable. Take it more as a pseudo-code than as a ready-to-use function. For something that you can use with no modification (but obviously much longer and complex), I suggest that you check out the github link I gave in the introduction</p>

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

<dt id="32"></dt><h2>Reading the values from the tree</h2>


<p>In a way, we could compare this process to any other compression / decompression module we're used to, such as cPickle, configParser or json, that we frequently use in maya. When creating the tree, we kind of 'json.dump()' our point array. But to take advantage of it, one would need to 'json.load()' it, i.e. uncompress it, so to speak.</p>

<p>Unfortunately, we're entering here in something muuuuuuuch more complex, which is <a href="https://en.wikipedia.org/wiki/K-nearest_neighbors_algorithm">a field of expertise on its own</a>, and an important challenge in modern computer graphics, from what I understood. Just like every low level algorithm (such as decomposition LU, or even worst, boolean algebra (I recommand this <a href="http://www.nand2tetris.org/book.php">amazing book</a>, if you're interested in this topic!)), it's the starting point of massive calculations (if you save 0.1s on an operation that you'll perform 50 000, you can easily see how significant would be even the smaller improvement on the base code !). Therefore, it opens the door to much more complex (and fun) tools, such as a proximity maps !</p>

<p>So I won't go into too much detail on that, firstly because I'm miles away from a good understanding, and secondly because I wouldn't even know where to start. The few last days I spent in researches showed me that there are a few different implementations, and I definetly don't have time (and probably knowledge ^^) to benchmark all of them ! </p>

<p>Now why is that so efficient ? The general idea, as we said, is that we can isolate super quickly the closest element using a boolean logic. Regarding the tree hierarchy, and with a given point P, we'll compare P.x with the root.x. If greater, we look for the nearest point in the right part, otherwise in the left part. it's 0 or 1, true or false, left or right. Same thing with the next level, and so on. The limitation, of course, is that unlike the getClosestPoint(), you'll get the closest vertex, not the closest point. The heavier the mesh is, the smaller the error will be, and the faster the processing will be, compared to the getClosestPoint() method. But in the case of a super low poly mesh, obviously, it'd probably be better to use the getClosestPoint instead, as the time would't be a problem.</p>

<p>I'd advice, if you want to go further, to have a look at the siggraph papers, or some other technical papers you can find on the internet. For my implementation, I sticked to <a href="http://pubs.cs.uct.ac.za/archive/00000847/01/kd-backtrack-techrep.pdf">this paper</a>, more because it was easy than because of a real choice. The dudes talk about computer science, so I assumed it was pretty similar to my needs and requirements. If you're really interested, do not hesitate to let me know in the comments, and I'll try to debunk the concept, at least for the implementation i choosed !</p>

<p>The interest, ultimately, is to be able to query very quickly the closest point, and therefore, the distance that separates this closest point from the query point, the exact position of this vertex in world space, the index of this vertex, etc... Considering that there is almost no calculation, just a particular method of sorting huge arrays, you can attach any info you want against each element of your array !</p>


<dt id="40"></dt><h1>Conclusion</h1>

<p>So all those stuff brought us far from the initial problem ! But to motivate you looking into k-d trees, i replaced the call to getClosestPoint() by my implementation of a k-d tree, and go from 8.62844705582s down to 1.25752019882s of evaluation (600% improvement, booom !).
And I'm sure i can optimize it, <u>and</u> this is just pure python ! Imagine the result with a decent c++ version !</p>

<p>I hope that this small introduction to k-d trees will motivate you looking at it. If you want to know more (in particular on how to get the results back), do not hesitate to let me know in the comments. On my side, I must confess that gives me tons of ideas of tools ! So time to work !</p>

<?php addVideo("190039595", $type="vimeo", $width=640, $height=400);?>