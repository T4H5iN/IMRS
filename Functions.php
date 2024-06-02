<?php
session_start();

function Info($row): void
{
    $imdbId = $row['id'];
    echo "<div class='movie-container'>";
    echo "<div style='display: flex; flex-direction: column'>";
    echo "<div class='image-and-buttons' style='position: relative;'>";
    echo "<img src='/Poster/$imdbId.jpg' alt='Movie Image' class='movie-poster'>";
    echo "<div class='button-container'>";
    echo "<div class='rate-on'>
            <img src='/Illustrations/rate.png' alt='Rate'>
            <div class='rate-tooltip'>
                <form action='Rate.php' method='POST' id='ratingForm'>
                    <input type='hidden' name='id' value='$imdbId'>
                    <div class='scores'>";
    for ($i = 1; $i <= 10; $i++) {
        echo "<button type='submit' name='rating' value='$i'>$i</button>";
    }
    echo "</div></form></div></div>";
    echo "<div class='add-on'>
        <img src='/Illustrations/add.png' alt='Rate'>
        <div class='rate-tooltip'>
            <form action='Add_to_List.php' method='POST' id='ratingForm'>
                <input type='hidden' name='id' value='$imdbId'>
                <div class='scores'>";

    echo "<button type='submit' name='list' style='width: fit-content; font-size: 15px' value='Watchlist'>Watchlist</button>";
    include 'db_connect.php';
    $username = $_SESSION['username'];
    $sql = "SELECT listName FROM List_name WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $userLists = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($userLists as $list) {
        $listName = $list['listName'];
        echo "<button type='submit' name='list' style='width: fit-content; font-size: 15px' value='$listName'>$listName</button>";
    }

    echo "</div></form></div></div>";
    echo "<a href='Favourite.php?id=$imdbId'><img src='/Illustrations/fav.png' class='fav-on'></a>";
    echo "</div>";
    echo "</div><br>";
            if (isset($_GET['errorFav'])) {
                echo "<div id='success-fav' class='success'>";
                echo htmlspecialchars($_GET['errorFav']);
                echo "</div>";
            }else if (isset($_GET['errorFa'])) {
                echo "<div id='error-fav' class='error-message'>";
                echo htmlspecialchars($_GET['errorFa']);
                echo "</div>";
            }else if (isset($_GET['errorRat'])) {
                echo "<div id='success-rate' class='success'>";
                echo htmlspecialchars($_GET['errorRat']);
                echo "</div>";
            }else if (isset($_GET['errorRa'])) {
                echo "<div id='error-rate' class='error-message'>";
                echo htmlspecialchars($_GET['errorRa']);
                echo "</div>";
            }else if (isset($_GET['errorAdd'])) {
                echo "<div id='success-add' class='success'>";
                echo htmlspecialchars($_GET['errorAdd']);
                echo "</div>";
            }else if (isset($_GET['errorAd'])) {
                echo "<div id='error-add' class='error-message'>";
                echo htmlspecialchars($_GET['errorAd']);
                echo "</div>";
            }
        echo "</div>";
    echo "<div class='movie-details'>";
    echo "<div class='title'> " . $row["primaryTitle"] . "</div>";
    if ($row["primaryTitle"] !== $row["originalTitle"]) {
        echo "<div class='original-title'>Original Title: " . $row["originalTitle"] . "</div>";
    } else {
        echo "<br>";
    }
    echo "<div class='info'>";
}

function Info_output($row): void
{
    Output_Info($row);
    $imdbId = $row['id'];
    include 'db_connect.php';

    echo "<br><span style='text-wrap: nowrap'>" . $row["plot"] . "</span><br><br>";
    try {
        $sql = "SELECT director FROM directors WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $imdbId, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<br><span><b style='font-size: 17px'>Director: </b>";

            $first = true;
            while ($genre_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!$first) {
                    echo "&nbsp  ·  &nbsp";
                } else {
                    $first = false;
                }

                echo $genre_row["director"];
            }
            echo "</span><br>";
        }
    } catch (PDOException $e) {
        echo "Error fetching genres: " . $e->getMessage();
    }
    try {
        $sql = "SELECT writer FROM writers WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $imdbId, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<br><span><b style='font-size: 17px'>Writers: </b>";

            $first = true;
            while ($genre_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!$first) {
                    echo "&nbsp  ·  &nbsp";
                } else {
                    $first = false;
                }

                echo $genre_row["writer"];
            }
            echo "</span><br>";
        }
    } catch (PDOException $e) {
        echo "Error fetching genres: " . $e->getMessage();
    }
    try {
        $sql = "SELECT actor FROM actors WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $imdbId, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<br><span><b style='font-size: 17px'>Stars: </b>";

            $first = true;
            while ($genre_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!$first) {
                    echo "&nbsp  ·  &nbsp";
                } else {
                    $first = false;
                }

                echo $genre_row["actor"];
            }
            echo "</span><br>";
        }
    } catch (PDOException $e) {
        echo "Error fetching genres: " . $e->getMessage();
    }
    echo"</div></div></div>";
}

function Output_Info($row): void
{
    $imdbId = $row['id'];
    $hours = floor($row["runtime"] / 60);
    $minutes = $row["runtime"] % 60;
    if ($hours > 0) {
        echo "<span class='runtime'>" . $hours . 'h ';
    }
    if ($minutes > 0) {
        echo $minutes . 'min';
    }
    echo "</span>";
    include 'db_connect.php';
    try {
        $sql = "SELECT genre FROM genres WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $imdbId, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "<div class='genre'>";

            $first = true;
            while ($genre_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!$first) {
                    echo "&nbsp  ·  &nbsp";
                } else {
                    $first = false;
                }
                echo $genre_row["genre"];
            }
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "Error fetching genres: " . $e->getMessage();
    }
    echo "<div class='rating'>";
    echo "<span class='score'>" . $row["rating"] . "</span>";
    $votes = (int)$row["noOfVotes"];
    if ($votes >= 1000000) {
        echo "<span class='votes'>(" . number_format($votes / 1000000, 1) . "M)</span>";
    } elseif ($votes >= 1000) {
        echo "<span class='votes'>(" . number_format($votes / 1000) . "K)</span>";
    } else {
        echo "<span class='votes'>(" . $votes . ")</span>";
    }
    echo "</div><br>";
}

function pagination($currentPage, $totalPages): void
{
    $queryParams = $_GET;
    unset($queryParams['page']);
    $queryString = http_build_query($queryParams);

    echo "<div class='pagination'>";
    $maxPagesToShow = 7;

    $startRange = max(1, $currentPage - floor($maxPagesToShow / 2));
    $endRange = min($totalPages, $startRange + $maxPagesToShow - 1);

    if ($currentPage > 1) {
        echo "<a href='?" . $queryString . "&page=" . ($currentPage - 1) . "'>&lt;</a>";
    }

    if ($startRange > 1) {
        echo "<a href='?" . $queryString . "&page=1'>1</a>";
        if ($startRange > 2) {
            echo "<span>...</span>";
        }
    }

    for ($i = $startRange; $i <= $endRange; $i++) {
        echo "<a href='?" . $queryString . "&page=$i' " . ($i == $currentPage ? "class='active'" : "") . ">$i</a> ";
    }

    if ($endRange < $totalPages) {
        if ($endRange < $totalPages - 1) {
            echo "<span>...</span>";
        }
        echo "<a href='?" . $queryString . "&page=$totalPages'>$totalPages</a>";
    }

    if ($currentPage < $totalPages) {
        echo "<a href='?" . $queryString . "&page=" . ($currentPage + 1) . "'>&gt;</a>";
    }

    echo "</div>";
}

function Search_Output($result): void
{
    echo "<div class='info-container'>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $imdbId = $row['id'];
        echo "<div class='movie-container'>";
        echo "<img src='/Poster/$imdbId.jpg' alt='Movie Image' class='movie-image'>";
        echo "<div class='movie-details'>";
        echo "<div class='title'><a href='Info.php?id=$imdbId'>";
        if (!empty($row["rank"])) {
            echo $row["rank"] . ". ";
        }
        echo $row["primaryTitle"] . "</a></div>";

        if ($row["primaryTitle"] !== $row["originalTitle"]) {
            echo "<div class='original-title'>Original Title: " . $row["originalTitle"] . "</div>";
        } else {
            echo "<br>";
        }
        echo "<div class='info'>";
        if($row["type"] == "movie"){
            echo "<span class='year'>" . $row["startYear"] . "&nbsp  ·  &nbsp</span>";
        }else{
            echo "<span class='year'>" . $row["startYear"] . " - " . $row["endYear"] . "&nbsp  ·  &nbsp</span>";
        }
        $hours = floor($row["runtime"] / 60);
        $minutes = $row["runtime"] % 60;
        if ($hours > 0) {
            echo "<span class='runtime'>" . $hours . 'h ';
        }
        if ($minutes > 0) {
            echo $minutes . 'min';
        }
        echo "&nbsp  ·  &nbsp</span>";
        if($row["type"] == "movie") {
            echo "<span class='genre'>Movie</span>";
        }else if($row["type"] == "tvSeries"){
            echo "<span class='genre'>TV Series</span>";
        }else{
            echo "<span class='genre'>TV Mini Series</span>";
        }
        include 'db_connect.php';
        try {
            $sql = "SELECT genre FROM genres WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $imdbId, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<div class='genre'>";

                $first = true;
                while ($genre_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if (!$first) {
                        echo "&nbsp  ·  &nbsp";
                    } else {
                        $first = false;
                    }
                    echo $genre_row["genre"];
                }
                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "Error fetching genres: " . $e->getMessage();
        }

        echo "<div class='rating'>";
        echo "<span class='score'>" . $row["rating"] . "</span>";
        $votes = (int)$row["noOfVotes"];
        if ($votes >= 1000000) {
            echo "<span class='votes'>(" . number_format($votes / 1000000, 1) . "M)</span>";
        } elseif ($votes >= 1000) {
            echo "<span class='votes'>(" . number_format($votes / 1000) . "K)</span>";
        } else {
            echo "<span class='votes'>(" . $votes . ")</span>";
        }
        echo "</div><br>";
        echo "  
            <div class='button-container'>
            <div class='rate-one'>
            <button class='rate-button'>Rate</button>
            <div class='rate-tooltip'>
                <form action='Rate.php' method='POST' id='ratingForm'>
                    <input type='hidden' name='id' value='$imdbId'>
                    <div class='scores'>";
        for ($i = 1; $i <= 10; $i++) {
            echo "<button type='submit' name='rating' value='$i'>$i</button>";
        }
        echo "</div></form></div></div>";
        echo "
                <a href='Favourite.php?id=$imdbId'><button class='rate-button' style='width: 110px'>Favourite</button></a>
            </div>
            </div></div></div>";
    }
    echo "</div>";
}

function Filtered_Output(): void
{
    echo "<div class='filter-container'>";
    echo "<h1>Advanced Search</h1>";
    echo "<h2>Sort</h2><hr>";
    $selectedSort = $_GET['sort'] ?? 'rating';
    $selectedOrder = $_GET['sort-order'] ?? 'asc';

    // Start a single form for both sorting and filtering
    echo "<form action='Advanced_Search.php' method='get'>";

    echo "<select id='sort' name='sort' style='margin-right: 5px'>";
    $options = ['rating' => 'Rating', 'noOfVotes' => 'Number of Votes', 'runtime' => 'Runtime', 'startYear' => 'Release Date'];
    foreach ($options as $value => $label) {
        $selected = $value === $selectedSort ? 'selected' : '';
        echo "<option value='$value' $selected>&nbsp;&nbsp;$label</option>";
    }
    echo "</select>";

    echo "<select id='sort-order' name='sort-order' style='width: 120px;margin-right: 5px'>";
    $options = ['asc' => 'Ascending', 'desc' => 'Descending'];
    foreach ($options as $value => $label) {
        $selected = $value === $selectedOrder ? 'selected' : '';
        echo "<option value='$value' $selected>&nbsp;&nbsp;$label</option>";
    }
    echo "</select>";
    echo "<button type='submit' style='width: 100px'>Sort</button>";

    echo "<h2>Filter</h2><hr>";
    echo '<label for="search">Title:</label><input type="text" name="title" placeholder="Search for anything..." style="width: 350px;margin-bottom: 7px">';

    echo '<div class="cbox-container">
        <ul class="ks-cboxtags">
            <label for="checkbox" style="font-size: 16px">Type:</label>
            <li><input type="checkbox" id="checkboxOne" name="types[]" value="movie"><label for="checkboxOne">Movie</label></li>
            <li><input type="checkbox" id="checkboxTwo" name="types[]" value="tvSeries"><label for="checkboxTwo">TV Series</label></li>
            <li><input type="checkbox" id="checkboxThree" name="types[]" value="tvMiniSeries"><label for="checkboxThree">TV Mini Series</label></li>
        </ul>
      </div>';

    echo "<label for='release_year'>Release Year:</label><input type='text' id='release_year1' name='release_year1' placeholder='YYYY' style='width: 50px'> to <input type='text' id='release_year2' name='release_year2' placeholder='YYYY' style='width: 50px'>";

    echo "<label for='rating_range'><br>Rating:</label><input type='text' id='rating_range1' name='rating_range1' placeholder='e.g. 5' style='width: 50px'> to <input type='text' id='rating_range2' name='rating_range2' placeholder='e.g. 9.5' style='width: 60px'>";

    echo "<label for='votes_range'><br>Number of Votes:</label><input type='text' id='votes_range1' name='votes_range1' placeholder='e.g. 50000' style='width: 85px'> to <input type='text' id='votes_range2' name='votes_range2' placeholder='e.g. 2000000' style='width: 100px'>";

    echo "<label for='runtime_range'><br>Runtime:</label><input type='text' id='runtime_range1' name='runtime_range1' placeholder='e.g. 20' style='width: 60px'> to <input type='text' id='runtime_range2' name='runtime_range2' placeholder='e.g. 150' style='width: 60px'>";

    echo '<div id="error-mail" class="error-message">';
        if (isset($_GET['errorS'])) {
            echo htmlspecialchars($_GET['errorS']);
        }
    echo "</div>";
    echo "<br><button type='submit' style='width: 150px;margin-bottom: 30px'>Apply Filters</button>";
    echo "</form>";
    echo "</div>";
}

function calculatePercentage($rate, $conn): float|int
{
    try {
        $username = $_SESSION['username'];
        $sql = "SELECT COUNT(*) as count FROM userRating WHERE username = :username AND rating = :rate";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':rate', $rate);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $filteredCount = $result['count'];

        $sqlTotal = "SELECT COUNT(*) FROM userRating WHERE username= :username";
        $stmtTotal = $conn->prepare($sqlTotal);
        $stmtTotal->bindParam(':username', $username);
        $stmtTotal->execute();

        $totalCount = $stmtTotal->fetchColumn();

        return ($filteredCount / $totalCount) * 100;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

function calculateGenrePercentage($genre, $conn): float|int
{
    try {
        session_start();
        $username = $_SESSION['username'];

        $sql = "
            SELECT COUNT(*) as count FROM (
                SELECT titles.id FROM titles
                JOIN userRating ON titles.id = userRating.titleid
                JOIN genres ON titles.id = genres.id
                WHERE genres.genre = :genre AND userRating.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN List ON titles.id = List.titleID
                JOIN genres ON titles.id = genres.id
                WHERE genres.genre = :genre AND List.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN Favourite ON titles.id = Favourite.titleid
                JOIN genres ON titles.id = genres.id
                WHERE genres.genre = :genre AND Favourite.username = :username
            ) as combined
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $filteredCount = $result['count'];

        $totalCountSql = "
            SELECT COUNT(*) FROM (
                SELECT titles.id FROM titles
                JOIN userRating ON titles.id = userRating.titleid
                WHERE userRating.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN List ON titles.id = List.titleID
                WHERE List.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN Favourite ON titles.id = Favourite.titleid
                WHERE Favourite.username = :username
            ) as combined
        ";

        $totalCountStmt = $conn->prepare($totalCountSql);
        $totalCountStmt->bindParam(':username', $username);
        $totalCountStmt->execute();
        $total = $totalCountStmt->fetchColumn();

        return ($filteredCount / $total) * 100;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}


function calculateGenrePercentages($genresToDisplay, $conn, $username): array
{
    try {
        $genrePercentages = [];

        foreach ($genresToDisplay as $genre) {
            $sql = "
            SELECT COUNT(*) as count FROM (
                SELECT titles.id FROM titles
                JOIN userRating ON titles.id = userRating.titleid
                JOIN genres ON titles.id = genres.id
                WHERE genres.genre = :genre AND userRating.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN List ON titles.id = List.titleID
                JOIN genres ON titles.id = genres.id
                WHERE genres.genre = :genre AND List.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN Favourite ON titles.id = Favourite.titleid
                JOIN genres ON titles.id = genres.id
                WHERE genres.genre = :genre AND Favourite.username = :username
            ) as combined
        ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $filteredCount = $result['count'];

            $totalCountSql = "
            SELECT COUNT(*) FROM (
                SELECT titles.id FROM titles
                JOIN userRating ON titles.id = userRating.titleid
                WHERE userRating.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN List ON titles.id = List.titleID
                WHERE List.username = :username
                UNION
                SELECT titles.id FROM titles
                JOIN Favourite ON titles.id = Favourite.titleid
                WHERE Favourite.username = :username
            ) as combined
        ";

            $totalCountStmt = $conn->prepare($totalCountSql);
            $totalCountStmt->bindParam(':username', $username);
            $totalCountStmt->execute();
            $total = $totalCountStmt->fetchColumn();

            $genrePercentages[$genre] = ($total > 0) ? ($filteredCount / $total) * 100 : 0;
        }

        return $genrePercentages;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

function recommendContent($conn, $username, $type) {
    $genresToDisplay = ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Drama', 'Family', 'Fantasy', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'Romance', 'Sci-Fi', 'Sport', 'Thriller', 'War', 'Western'];

    $genrePercentages = calculateGenrePercentages($genresToDisplay, $conn, $username);

    arsort($genrePercentages);
    $highestWatchedGenre = key($genrePercentages);

    $contentTypeCondition = ($type === 'movie') ? "AND t.type = 'movie'" : "AND t.type LIKE '%series%'";

    $sql = "
        SELECT t.id, t.primaryTitle, r.rating, r.noOfVotes 
        FROM titles t
        JOIN rating r ON t.id = r.id
        JOIN genres g ON t.id = g.id
        WHERE g.genre = :highestWatchedGenre
        $contentTypeCondition
        AND NOT EXISTS (
            SELECT 1 FROM List l WHERE l.titleID = t.id AND l.username = :username
        )
        AND NOT EXISTS (
            SELECT 1 FROM userRating ur WHERE ur.titleID = t.id AND ur.username = :username
        )
        AND NOT EXISTS (
            SELECT 1 FROM Favourite f WHERE f.titleID = t.id AND f.username = :username
        )
        ORDER BY r.noOfVotes DESC
        LIMIT 5
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':highestWatchedGenre', $highestWatchedGenre);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTopRated($conn, $type, $limit) {
    $username = $_SESSION['username'];
    $contentTypeCondition = ($type === 'movie') ? "AND t.type = 'movie'" : "AND t.type LIKE '%series%'";

    $sql = "
        SELECT t.id, t.primaryTitle, r.rating, r.noOfVotes 
        FROM titles t
        JOIN rating r ON t.id = r.id
        WHERE r.rating IS NOT NULL
        $contentTypeCondition
        AND NOT EXISTS (
            SELECT 1 FROM List l WHERE l.titleID = t.id AND l.username = :username
        )
        AND NOT EXISTS (
            SELECT 1 FROM userRating ur WHERE ur.titleID = t.id AND ur.username = :username
        )
        AND NOT EXISTS (
            SELECT 1 FROM Favourite f WHERE f.titleID = t.id AND f.username = :username
        )
        ORDER BY r.rating DESC, r.noOfVotes DESC
        LIMIT :limit
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getPopular($conn, $type, $limit) {
    $username = $_SESSION['username'];
    $contentTypeCondition = ($type === 'movie') ? "AND t.type = 'movie'" : "AND t.type LIKE '%series%'";

    $sql = "
        SELECT t.id, t.primaryTitle, r.rating, r.noOfVotes 
        FROM titles t
        JOIN rating r ON t.id = r.id
        WHERE r.noOfVotes IS NOT NULL
        $contentTypeCondition
        AND NOT EXISTS (
            SELECT 1 FROM List l WHERE l.titleID = t.id AND l.username = :username
        )
        AND NOT EXISTS (
            SELECT 1 FROM userRating ur WHERE ur.titleID = t.id AND ur.username = :username
        )
        AND NOT EXISTS (
            SELECT 1 FROM Favourite f WHERE f.titleID = t.id AND f.username = :username
        )
        ORDER BY r.noOfVotes DESC
        LIMIT :limit
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
