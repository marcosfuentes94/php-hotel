<!DOCTYPE html>
<html>
<head>
    <title>PHP Hotel</title>
      <!-- BOOTSTRAP -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
       <!-- CSS -->
      <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
// INCLUDI I DATI DEGLI HOTEL
include 'hotel_data.php';

// COPIA DEI DATI ORIGINALI DEGLI HOTEL
$originalHotels = $hotels;

// RECUPERA I FILTRI DALLA QUERY STRING
$parkingFilter = isset($_GET['parking']) ? $_GET['parking'] : null;
$voteFilter = isset($_GET['vote']) ? $_GET['vote'] : null;

// APPLICA I FILTRI SOLO SE SONO STATI IMPOSTATI
if ($parkingFilter !== null || $voteFilter !== null) {
    // FILTRA GLI HOTEL CON I FILTRI SELEZIONATI
    $filteredHotels = array_filter($originalHotels, function ($hotel) {
        global $parkingFilter, $voteFilter;
        return hasParking($hotel) && hasMinVote($hotel);
    });
} else {
    // SE NON CI SONO FILTRI, MOSTRA TUTTI GLI HOTEL
    $filteredHotels = $originalHotels;
}

// FUNZIONE PER VERIFICARE IL PARCHEGGIO DELL'HOTEL
function hasParking($hotel) {
    global $parkingFilter;
    return $parkingFilter === null || ($parkingFilter == '1' && $hotel['parking']) || ($parkingFilter == '0' && !$hotel['parking']) || $parkingFilter === '';
}

// FUNZIONE PER VERIFICARE IL VOTO MINIMO DELL'HOTEL
function hasMinVote($hotel) {
    global $voteFilter;
    return $voteFilter === null || $hotel['vote'] >= intval($voteFilter);
}

// RESETTA I FILTRI QUANDO L'UTENTE CLICCA SU "RIMUOVI FILTRI"
if (isset($_GET['clear_filters']) && $_GET['clear_filters'] === 'true') {
    $parkingFilter = null;
    $voteFilter = null;
    $filteredHotels = $originalHotels;
}
?>

<div class="container">
    <h1>PHP Hotel</h1>
    <form method="GET" class="mb-3">
        <div class="form-row">
            <div class="form-group col-md-3">
                <!-- FILTRO PER IL PARCHEGGIO -->
                <label for="parking">Parcheggio:</label>
                <select id="parking" name="parking" class="form-control">
                    <option value="" <?php if ($parkingFilter === '') echo 'selected'; ?>>Tutti</option>
                    <option value="1" <?php if ($parkingFilter === '1') echo 'selected'; ?>>Con parcheggio</option>
                    <option value="0" <?php if ($parkingFilter === '0') echo 'selected'; ?>>Senza parcheggio</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <!-- FILTRO PER IL VOTO MINIMO -->
                <label for="vote">Voto minimo:</label>
                <input type="number" id="vote" name="vote" class="form-control" min="1" max="5" step="1" value="<?php echo $voteFilter; ?>">
            </div>
            <div class="form-group col-md-2">
                <!-- BOTTONE PER APPLICARE I FILTRI -->
                <button type="submit" class="btn btn-primary">Filtra</button>
            </div>
            <div class="form-group col-md-2">
                <!-- LINK PER RIMUOVERE I FILTRI -->
                <a href="index.php?clear_filters=true" class="btn btn-danger">Rimuovi filtri</a>
            </div>
        </div>
    </form>

    <div class="row">
        <?php foreach ($filteredHotels as $hotel) {
            // STAMPA LE INFORMAZIONI DELL'HOTEL
            echo '<div class="col-md-6 mb-4">';
            echo '<div class="card">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $hotel['name'] . '</h5>';
            echo '<p class="card-text">' . $hotel['description'] . '</p>';
            echo '<p class="card-text">Voto: ' . $hotel['vote'] . '</p>';
            echo '<p class="card-text">Distanza dal centro: ' . $hotel['distance_to_center'] . ' km</p>';
            echo '<p class="card-text">Parcheggio: ' . ($hotel['parking'] ? 'Disponibile' : 'Non disponibile') . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } ?>
    </div>
</div>

</body>
</html>
