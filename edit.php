<?php

require_once('./connection.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

$bookAuthorsStmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$bookAuthorsStmt->execute(['id' => $id]);

$availableAuthorsStmt = $pdo->prepare('SELECT * FROM authors WHERE id NOT IN (SELECT author_id FROM book_authors WHERE book_id = :book_id)');
$availableAuthorsStmt->execute(['book_id' => $id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <nav>
        <a href="./book.php?id=<?= $id; ?>">Tagasi</a>
    </nav>
    <br>

    <h3><?= $book['title'];?></h3>

    <form action="./update_book.php?id=<?= $id; ?>" method="post">
        <label for="title">Pealkiri:</label>
        <input type="text" name="title" value="<?= $book['title'];?>">
        <br>
        <label for="price">Hind:</label>
        <input type="text" name="price" value="<?= $book['price'];?>">
        <br><br>
        <input type="submit" name="action" value="Salvesta">
    </form>
   
<br><br>

    <h3>Autorid:</h3>

    <ul>
        <?php while ( $author = $bookAuthorsStmt->fetch() ) { ?>
            
            <li>
                <form action="./remove_author.php?id=<?= $id; ?>" method="post">
                    <?= $author['first_name']; ?>
                    <?= $author['last_name']; ?>
                    <button type="submit" name="action" value="remove_author" style="cursor: pointer; background-color: transparent; border: 1px solid;">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 64 64"><ellipse cx="32" cy="61" opacity=".3" rx="19" ry="3"></ellipse>
                            <path fill="#9c34c2" d="M43.299,55H20.701c-1.535,0-2.823-1.159-2.984-2.686L14,17h36l-3.717,35.314	C46.122,53.841,44.834,55,43.299,55z"></path><path fill="#ffa1ac" d="M50,22H14c-1.657,0-3-1.343-3-3v-2c0-1.657,1.343-3,3-3h36c1.657,0,3,1.343,3,3v2	C53,20.657,51.657,22,50,22z"></path><path d="M43.965,26.469l-2.248,21.757C41.602,49.237,40.746,50,39.729,50H33c-2.762,0-4.997,2.239-4.997,5	h15.296c1.535,0,2.823-1.159,2.984-2.686l3.152-30.249C46.712,21.784,44.274,23.747,43.965,26.469z" opacity=".15"></path><path fill="#fff" d="M21.111,37.65l-1.585-16.205c-0.004-0.04-0.009-0.08-0.015-0.119	C19.346,20.102,20.244,19,21.48,19h9.385c2.762,0,4.997-2.239,4.997-5H14c-1.657,0-3,1.343-3,3v2c0,1.657,1.343,3,3,3h0.558	l2.139,21.174C19.441,42.868,21.418,40.395,21.111,37.65z" opacity=".3"></path><line x1="17.5" x2="23.5" y1="17.5" y2="17.5" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"></line><path fill="#9c34c2" d="M39,14H25v-2c0-1.657,1.343-3,3-3h8c1.657,0,3,1.343,3,3V14z"></path>
                        </svg>                            
                        </button>
                    <input type="hidden" name="author_id" value="<?= $author['id']; ?>">
                </form>
            </li>
        
        <?php } ?>
    </ul>

    <form action="./add_author.php" method="post">

        <input type="hidden" name="book_id" value="<?= $id; ?>">

        <select name="author_id">
    
            <option value=""></option>
        
        <?php while ( $author = $availableAuthorsStmt->fetch() ) { ?>
            <option value="<?= $author['id']; ?>">
                <?= $author['first_name']; ?>
                <?= $author['last_name']; ?>
            </option>
        <?php } ?>

        </select>
            <h4>Lisa uus autor:</h4>
            <input type="text" name="new_author_first_name" placeholder="First Name">
            <input type="text" name="new_author_last_name" placeholder="Last Name">
            
            <button type="submit" name="action" value="add_author">
                Add Author
            </button>

    </form>

</body>
</html>