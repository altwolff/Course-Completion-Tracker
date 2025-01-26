<?php
session_start();

if (!isset($_SESSION['sessions'])) {
    $_SESSION['sessions'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['session_date'])) {
    $session_date = $_POST['session_date'];
    $session_number = count($_SESSION['sessions']) + 1;

    $_SESSION['sessions'][] = [
        'session_number' => $session_number,
        'session_date' => $session_date
    ];

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_GET['delete'])) {
    $delete_index = $_GET['delete'];
    unset($_SESSION['sessions'][$delete_index]);
    $_SESSION['sessions'] = array_values($_SESSION['sessions']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['clear_sessions'])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$progress = (count($_SESSION['sessions']) / 7) * 100;
if ($progress > 100) {
    $progress = 100;
}

?>

<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="owr-logo.svg" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">

    <title>Kurs-Tracker | @altwolff</title>
</head>
<body>
<div class="text-center">
    <h2 class="roboto-thin m-3">Österreichische Wasserrettung <i class="bi bi-life-preserver"></i></h2>

    <button class="btn btn-primary m-3" data-bs-toggle="modal" data-bs-target="#sessionModal">Sitzung hinzufügen
    </button>

    <button class="btn btn-danger m-3" data-bs-toggle="modal" data-bs-target="#clearSessionsModal">Alle Sitzungen
        löschen
    </button>

    <div class="progress m-3" role="progressbar" aria-label="Example with label" aria-valuenow="<?= $progress ?>"
         aria-valuemin="0"
         aria-valuemax="100">
        <div class="progress-bar" style="width: <?= $progress ?>%"><?= round($progress) ?>%</div>
    </div>

    <?php if (!empty($_SESSION['sessions'])): ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Nr.</th>
                <th>Sitzungsdatum</th>
                <th>Aktion</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['sessions'] as $index => $session): ?>
                <tr>
                    <td><?= $session['session_number'] ?></td>
                    <td><?= $session['session_date'] ?></td>
                    <td>
                        <a href="?delete=<?= $index ?>" class="btn btn-danger btn-sm">Löschen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="m-3">Keine Sitzungen vorhanden.</p>
    <?php endif; ?>


    <?php if ($progress == 100): ?>
        <h3 class="m-3 text-success">Bereit für die Prüfung!</h3>
    <?php endif; ?>
</div>

<!-- Add Sessions Modal -->
<div class="modal fade" id="sessionModal" tabindex="-1" aria-labelledby="sessionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sessionModalLabel">Neue Sitzung hinzufügen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="session_date" class="form-label">Datum der Sitzung</label>
                        <input type="text" class="form-control" id="session_date" name="session_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Sitzung hinzufügen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Clear Sessions Modal -->
<div class="modal fade" id="clearSessionsModal" tabindex="-1" aria-labelledby="clearSessionsModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearSessionsModalLabel">Bestätigung erforderlich</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Möchten Sie alle Sitzungen löschen?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Abbrechen</button>
                <form method="POST" action="">
                    <button type="submit" name="clear_sessions" class="btn btn-danger">Bestätigen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {
        $('#session_date').datepicker({
            format: 'dd-mm-yyyy',
            language: 'de',
            autoclose: true
        });
    });
</script>
</body>
</html>