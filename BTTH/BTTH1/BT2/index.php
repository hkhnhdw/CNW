<?php
function loadQuestionsFromTxt() {
    $file = 'Quiz.txt';
    if (!file_exists($file)) {
        die("<h3 style='color:red'>Không tìm thấy file Quiz.txt trong thư mục!</h3>");
    }

    $content = file_get_contents($file);
    $lines = explode("\n", $content); 
    $current = null;

    foreach ($lines as $line) {
        $line = rtrim($line); 

        if (trim($line) !== '' && !preg_match('/^[A-D]\.\s/', $line) && !preg_match('/^ANSWER:/i', $line)) {
            if ($current !== null) {
                $questions[] = $current;
            }
            $current = [
                'question' => trim($line),
                'options' => [],
                'answer' => ''
            ];
        }
        elseif (preg_match('/^[A-D]\.\s*(.+)/', $line, $m)) {
            if ($current !== null) {
                $current['options'][] = trim($m[1]);
            }
        }
        elseif (preg_match('/^ANSWER:\s*(.+)/i', $line, $m)) {
            if ($current !== null) {
                $current['answer'] = trim($m[1]);
            }
        }
    }
    if ($current !== null && count($current['options']) >= 2) {
        $questions[] = $current;
    }

    return $questions;
}

// Load dữ liệu
$questions = loadQuestionsFromTxt();
$total = count($questions);

$score = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_quiz'])) {
    $score = 0;
    foreach ($questions as $i => $q) {
        $userAnswer = $_POST["q$i"] ?? '';
        $correct = $q['answer'];

        $correctArr = array_map('trim', explode(',', $correct));
        $userArr = is_array($userAnswer) ? $userAnswer : ($userAnswer == '' ? [] : [$userAnswer]);

        sort($correctArr);
        sort($userArr);

        if ($correctArr === $userArr) $score++;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thi Trắc Nghiệm Android</title>
</head>
<body>

<div class="container">
    <h1>Thi Trắc Nghiệm Android</h1>
    <div class="info">Tổng cộng: <strong><?= $total ?></strong> câu hỏi</div>

    <?php if ($score !== null): ?>
        <div class="result">
            <h2>Kết quả của bạn</h2>
            <p>Đúng: <strong><?= $score ?> / <?= $total ?></strong></p>
            <br>
            <a href="">Làm lại bài thi</a>
        </div>
    <?php else: ?>
        <form method="POST">
            <?php foreach ($questions as $i => $q): 
                $isMultiple = strpos($q['answer'], ',') !== false;
                $letters = ['A', 'B', 'C', 'D'];
            ?>
                <div class="question">
                    <strong>Câu <?= $i + 1 ?>:</strong> <p><?= htmlspecialchars($q['question']) ?></p>
                    <?php foreach ($q['options'] as $j => $opt): ?>
                        <label>
                            <input type="<?= $isMultiple ? 'checkbox' : 'radio' ?>"
                                   name="q<?= $i ?><?= $isMultiple ? '[]' : '' ?>"
                                   value="<?= $letters[$j] ?>">
                            <strong><?= $letters[$j] ?>.</strong> <?= htmlspecialchars($opt) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" name="submit_quiz" class="submit">Nộp Bài</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>