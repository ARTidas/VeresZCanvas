<?php

    /* ********************************************************
     * ********************************************************
     * ********************************************************/
    class DigitViewView extends ProjectAbstractView {

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayHTMLOpen() {
            ?>
                <!doctype html>
                <html lang="en-US">
                <head>
                    <title><?php print($this->do->title); ?></title>

                    <meta charset="UTF-8" />
                    <meta http-equiv="content-type" content="text/html" />
                    <meta name="description" content="<?php print($this->do->description); ?>" />
                    <meta http-equiv="cache-control" content="max-age=0" />
                    <meta http-equiv="cache-control" content="no-cache" />
                    <meta http-equiv="expires" content="0" />
                    <meta http-equiv="pragma" content="no-cache" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">

                    <link rel="stylesheet" href="<?php print(RequestHelper::$common_url_root); ?>/css/menu.css" type="text/css" />
                    <link rel="stylesheet" href="<?php print(RequestHelper::$common_url_root); ?>/css/index.css" type="text/css" />
                    <link rel="stylesheet" href="<?php print(RequestHelper::$url_root); ?>/css/index.css" type="text/css" />

                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/jquery/jquery.js"></script>
                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/nav_menu_dropdown.js"></script>

                    <script type="text/javascript" src="<?php print(RequestHelper::$common_url_root); ?>/js/D3/d3.js"></script>
                </head>
            <?php
        }

        /* ********************************************************
         * ********************************************************
         * ********************************************************/
        public function displayContent() {
            // Initialize the confusion matrix array
            $confusionMatrix = [];
            for ($i = 0; $i <= 9; $i++) {
                $confusionMatrix[$i] = array_fill(0, 10, 0);
            }

            // Data for visualizations
            $allSubmissions = [];

            // Populate the confusion matrix and prepare data for time series
            foreach ($this->do->do_list as $do) {
                $target = $do->target_digit;
                $predicted = $do->predicted_digit;
                if (isset($confusionMatrix[$target]) && isset($confusionMatrix[$target][$predicted])) {
                    $confusionMatrix[$target][$predicted]++;
                }

                $allSubmissions[] = [
                    'submitted_at' => strtotime($do->submitted_at) * 1000, // Convert to milliseconds for JS
                    'correct' => ($target === $predicted),
                    'confidence' => $do->confidence,
                ];
            }

            // Convert to JSON for JavaScript
            $confusionMatrixJson = json_encode($confusionMatrix);
            $allSubmissionsJson = json_encode($allSubmissions);

            ?>


				
                <h2>Accuracy Over Time</h2>
                <div id="accuracy-over-time-container"></div>
                <hr/>

                <h2>Confidence Over Time</h2>
                <div id="confidence-over-time-container"></div>
                <hr/>


                <h2>Confusion Matrix</h2>
                <div id="confusion-matrix-container"></div>
                <hr/>

                <!-- <h2>Confidence Distribution</h2>
                <div id="confidence-distribution-container">
                    <div id="all-confidence-distribution">
                        <h3>All Predictions</h3>
                    </div>
                    <div class="digit-confidence-charts">
                        <?php for ($i = 0; $i <= 9; $i++): ?>
                            <div id="digit-<?php echo $i; ?>-confidence">
                                <h4>Digit <?php echo $i; ?></h4>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <hr/> -->

                <h2>List</h2>
                <!-- <table>
                    <thead>
                        <tr>
                            <th>Target</th>
                            <th>Predicted</th>
                            <th>Confidence</th>
                            <th>Submitted At</th>
                            <th>Drawn Digit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->do->do_list as $do): ?>
                            <tr>
                                <td><?php echo $do->target_digit; ?></td>
                                <td><?php echo $do->predicted_digit; ?></td>
                                <td><?php echo round($do->confidence, 2); ?></td>
                                <td><?php echo $do->submitted_at; ?></td>
                                <td><img src="<?php echo RequestHelper::$url_root . '/cdn/digits/digit_' . $do->id . '.png'; ?>" style="max-width: 50px; height: auto;"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table> -->
				<table>
					<thead>
						<tr>
							<?php
								for ($i = 0; $i <= 9; $i++) {
									print('<th>');
									print($i);
									print('</th>');
								}
							?>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php
								for ($i = 0; $i <= 9; $i++) {
									print('<td>');
									foreach ($this->do->do_list as $do) {
										if ($i === $do->target_digit) {
											//print($do->id . ',');
											print('<img src="' . RequestHelper::$url_root . '/cdn/digits/digit_' . $do->id . '.png' . '" />');
										}
									}
									print('</td>');
								}
							?>
						</tr>
					</tbody>
				</table>

                <style>
                    .digit-confidence-charts {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                        gap: 20px;
                        margin-top: 20px;
                    }
                    .digit-confidence-charts > div {
                        border: 1px solid #ccc;
                        padding: 10px;
                        border-radius: 5px;
                    }
                </style>

                <script>
                    const confusionData = <?php echo $confusionMatrixJson; ?>;
                    const allSubmissionsData = <?php echo $allSubmissionsJson; ?>;

                    // --- Confusion Matrix ---
                    const targetLabels = Array.from({ length: 10 }, (_, i) => i);
                    const predictedLabels = Array.from({ length: 10 }, (_, i) => i);

                    const cmMargin = { top: 40, right: 20, bottom: 40, left: 60 };
                    const cmGridSize = 30;
                    const cmWidth = predictedLabels.length * cmGridSize + cmMargin.left + cmMargin.right;
                    const cmHeight = targetLabels.length * cmGridSize + cmMargin.top + cmMargin.bottom;

                    const cmSvg = d3.select("#confusion-matrix-container")
                        .append("svg")
                        .attr("width", cmWidth)
                        .attr("height", cmHeight)
                        .append("g")
                        .attr("transform", `translate(<span class="math-inline">\{cmMargin\.left\},</span>{cmMargin.top})`);

                    const cmXScale = d3.scaleBand()
                        .domain(predictedLabels)
                        .range([0, predictedLabels.length * cmGridSize])
                        .padding(0.05);

                    const cmYScale = d3.scaleBand()
                        .domain(targetLabels)
                        .range([0, targetLabels.length * cmGridSize])
                        .padding(0.05);

                    const cmColorScale = d3.scaleLinear()
                        .domain([0, d3.max(Object.values(confusionData).flat())])
                        .range(["white", "steelblue"]);

                    cmSvg.selectAll(".cell")
                        .data(targetLabels.flatMap(target =>
                            predictedLabels.map(predicted => ({ target, predicted, value: confusionData[target][predicted] }))
                        ))
                        .enter().append("rect")
                        .attr("class", "cell")
                        .attr("x", d => cmXScale(d.predicted))
                        .attr("y", d => cmYScale(d.target))
                        .attr("width", cmXScale.bandwidth())
                        .attr("height", cmYScale.bandwidth())
                        .style("fill", d => cmColorScale(d.value));

                    cmSvg.selectAll(".cell-text")
                        .data(targetLabels.flatMap(target =>
                            predictedLabels.map(predicted => ({ target, predicted, value: confusionData[target][predicted] }))
                        ))
                        .enter().append("text")
                        .attr("class", "cell-text")
                        .attr("x", d => cmXScale(d.predicted) + cmXScale.bandwidth() / 2)
                        .attr("y", d => cmYScale(d.target) + cmYScale.bandwidth() / 2)
                        .attr("text-anchor", "middle")
                        .attr("dominant-baseline", "central")
                        .style("fill", d => d.value > d3.max(Object.values(confusionData).flat()) / 2 ? "white" : "black")
                        .text(d => d.value);

                    cmSvg.append("g")
                        .attr("transform", `translate(0, ${targetLabels.length * cmGridSize})`)
                        .call(d3.axisBottom(cmXScale));

                    cmSvg.append("g")
                        .call(d3.axisLeft(cmYScale));

                    cmSvg.append("text")
                        .attr("x", (predictedLabels.length * cmGridSize) / 2)
                        .attr("y", targetLabels.length * cmGridSize + cmMargin.bottom - 5)
                        .style("text-anchor", "middle")
                        .text("Predicted Digit");

                    cmSvg.append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", -cmMargin.left + 20)
                        .attr("x", -(targetLabels.length * cmGridSize) / 2)
                        .style("text-anchor", "middle")
                        .text("Target Digit");

                    // --- Confidence Distribution ---
                    const allConfidenceData = allSubmissionsData.map(d => ({ confidence: d.confidence, correct: d.correct }));

                    const allConfidences = allConfidenceData.map(d => d.confidence);
                    const allCorrectConfidences = allConfidenceData.filter(d => d.correct).map(d => d.confidence);
                    const allIncorrectConfidences = allConfidenceData.filter(d => !d.correct).map(d => d.confidence);

                    const allConfidenceMargin = { top: 20, right: 30, bottom: 50, left: 50 };
                    const allConfidenceWidth = 500 - allConfidenceMargin.left - allConfidenceMargin.right;
                    const allConfidenceHeight = 200 - allConfidenceMargin.top - allConfidenceMargin.bottom;

                    const allConfidenceSvg = d3.select("#all-confidence-distribution")
                        .append("svg")
                        .attr("width", allConfidenceWidth + allConfidenceMargin.left + allConfidenceMargin.right)
                        .attr("height", allConfidenceHeight + allConfidenceMargin.top + allConfidenceMargin.bottom)
                        .append("g")
                        .attr("transform", `translate(<span class="math-inline">\{allConfidenceMargin\.left\},</span>{allConfidenceMargin.top})`);

                    const allConfidenceX = d3.scaleLinear()
                        .domain([0, 1])
                        .range([0, allConfidenceWidth]);

                    allConfidenceSvg.append("g")
                        .attr("transform", `translate(0, ${allConfidenceHeight})`)
                        .call(d3.axisBottom(allConfidenceX).ticks(10));
                    allConfidenceSvg.append("text")
                        .attr("x", allConfidenceWidth / 2)
                        .attr("y", allConfidenceHeight + allConfidenceMargin.bottom - 5)
                        .style("text-anchor", "middle")
                        .text("Confidence Score");

                    const allConfidenceHistogram = d3.histogram()
                        .value(d => d.confidence)
                        .domain(allConfidenceX.domain())
                        .thresholds(allConfidenceX.ticks(20));

                    const allBins = allConfidenceHistogram(allConfidences);
                    const allCorrectBins = allConfidenceHistogram(allCorrectConfidences);
                    const allIncorrectBins = allConfidenceHistogram(allIncorrectConfidences);

                    const allConfidenceY = d3.scaleLinear()
                        .range([allConfidenceHeight, 0])
                        .domain([0, d3.max(allBins, d => d.length)]);

                    allConfidenceSvg.append("g")
                        .call(d3.axisLeft(allConfidenceY));
                    allConfidenceSvg.append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", -allConfidenceMargin.left + 20)
                        .attr("x", -allConfidenceHeight / 2)
                        .style("text-anchor", "middle")
                        .text("Frequency");

                    allConfidenceSvg.selectAll("rect.incorrect")
                        .data(allIncorrectBins)
                        .enter().append("rect")
                        .attr("class", "incorrect")
                        .attr("x", d => allConfidenceX(d.x0) + 1)
                        .attr("y", d => allConfidenceY(d.length))
                        .attr("width", d => allConfidenceX(d.x1) - allConfidenceX(d.x0) - 1)
                        .attr("height", d => allConfidenceHeight - allConfidenceY(d.length))
                        .style("fill", "salmon")
                        .style("opacity", 0.7);

                    allConfidenceSvg.selectAll("rect.correct")
                        .data(allCorrectBins)
                        .enter().append("rect")
                        .attr("class", "correct")
                        .attr("x", d => allConfidenceX(d.x0) + 1)
                        .attr("y", d => allConfidenceY(d.length))
                        .attr("width", d => allConfidenceX(d.x1) - allConfidenceX(d.x0) - 1)
                        .attr("height", d => allConfidenceHeight - allConfidenceY(d.length))
                        .style("fill", "lightgreen")
                        .style("opacity", 0.7);

                    for (let digit = 0; digit <= 9; digit++) {
                        const digitData = allSubmissionsData.filter(d => d.target === digit);
                        const digitConfidences = digitData.map(d => d.confidence);

                        const digitMargin = { top: 20, right: 30, bottom: 50, left: 50 };
                        const digitWidth = 300 - digitMargin.left - digitMargin.right;
                        const digitHeight = 150 - digitMargin.top - digitMargin.bottom;

                        const digitSvg = d3.select(`#digit-${digit}-confidence`)
                            .append("svg")
                            .attr("width", digitWidth + digitMargin.left + digitMargin.right)
                            .attr("height", digitHeight + digitMargin.top + digitMargin.bottom)
                            .append("g")
                            .attr("transform", `translate(<span class="math-inline">\{digitMargin\.left\},</span>{digitMargin.top})`);

                        const digitX = d3.scaleLinear()
                            .domain([0, 1])
                            .range([0, digitWidth]);

                        digitSvg.append("g")
                            .attr("transform", `translate(0, ${digitHeight})`)
                            .call(d3.axisBottom(digitX).ticks(5));
                        digitSvg.append("text")
                            .attr("x", digitWidth / 2)
                            .attr("y", digitHeight + digitMargin.bottom - 5)
                            .style("text-anchor", "middle")
                            .text("Confidence");

                        const digitHistogram = d3.histogram()
                            .value(d => d.confidence)
                            .domain(digitX.domain())
                            .thresholds(digitX.ticks(10));

                        const digitBins = digitHistogram(digitConfidences);

                        const digitY = d3.scaleLinear()
                            .range([digitHeight, 0])
                            .domain([0, d3.max(digitBins, d => d.length)]);

                        digitSvg.append("g")
                            .call(d3.axisLeft(digitY).ticks(3));
                        digitSvg.append("text")
                            .attr("transform", "rotate(-90)")
                            .attr("y", -digitMargin.left + 20)
                            .attr("x", -digitHeight / 2)
                            .style("text-anchor", "middle")
                            .text("Frequency");

                        digitSvg.selectAll("rect")
                            .data(digitBins)
                            .enter().append("rect")
                            .attr("x", d => digitX(d.x0) + 1)
                            .attr("y", d => digitY(d.length))
                            .attr("width", d => digitX(d.x1) - digitX(d.x0) - 1)
                            .attr("height", d => digitHeight - digitY(d.length))
                            .style("fill", "steelblue");
                    }

                    // --- Accuracy Over Time ---
                    const accuracyData = d3.rollup(allSubmissionsData,
                        v => d3.mean(v, d => d.correct ? 1 : 0),
                        d => d3.timeDay(new Date(d.submitted_at)) // Group by day
                    );
                    const accuracyTimeSeries = Array.from(accuracyData).sort((a, b) => a[0] - b[0]);

                    const accuracyMargin = { top: 20, right: 30, bottom: 50, left: 50 };
                    const accuracyWidth = (window.innerWidth * 0.95) - accuracyMargin.left - accuracyMargin.right;
                    const accuracyHeight = 200 - accuracyMargin.top - accuracyMargin.bottom;

                    const accuracySvg = d3.select("#accuracy-over-time-container")
                        .append("svg")
                        .attr("width", accuracyWidth + accuracyMargin.left + accuracyMargin.right)
                        .attr("height", accuracyHeight + accuracyMargin.top + accuracyMargin.bottom)
                        .append("g")
                        .attr("transform", `translate(${accuracyMargin.left},${accuracyMargin.top})`);

                    const accuracyX = d3.scaleTime()
                        .domain(d3.extent(accuracyTimeSeries, d => d[0]))
                        .range([0, accuracyWidth]);

                    accuracySvg.append("g")
                        .attr("transform", `translate(0, ${accuracyHeight})`)
                        .call(d3.axisBottom(accuracyX).tickFormat(d3.timeFormat("%Y-%m-%d")));
                    accuracySvg.append("text")
                        .attr("x", accuracyWidth / 2)
                        .attr("y", accuracyHeight + accuracyMargin.bottom - 5)
                        .style("text-anchor", "middle")
                        .text("Date");

                    const accuracyY = d3.scaleLinear()
                        .domain([0, 1])
                        .range([accuracyHeight, 0]);

                    accuracySvg.append("g")
                        .call(d3.axisLeft(accuracyY).tickFormat(d3.format(".0%")));
                    accuracySvg.append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", -accuracyMargin.left + 20)
                        .attr("x", -accuracyHeight / 2)
                        .style("text-anchor", "middle")
                        .text("Accuracy");

                    const accuracyLine = d3.line()
                        .x(d => accuracyX(d[0]))
                        .y(d => accuracyY(d[1]));

                    accuracySvg.append("path")
                        .datum(accuracyTimeSeries)
                        .attr("fill", "none")
                        .attr("stroke", "steelblue")
                        .attr("stroke-width", 2)
                        .attr("d", accuracyLine);

                    // --- Confidence Over Time ---
                    const confidenceOverTimeData = d3.rollup(allSubmissionsData,
                        v => d3.mean(v, d => d.confidence),
                        d => d3.timeDay(new Date(d.submitted_at)) // Group by day
                    );
                    const confidenceTimeSeries = Array.from(confidenceOverTimeData).sort((a, b) => a[0] - b[0]);

                    const confidenceMargin = { top: 20, right: 30, bottom: 50, left: 50 };
                    const confidenceWidth = (window.innerWidth * 0.95) - confidenceMargin.left - confidenceMargin.right;
                    const confidenceHeight = 200 - confidenceMargin.top - confidenceMargin.bottom;

                    const confidenceSvg = d3.select("#confidence-over-time-container")
                        .append("svg")
                        .attr("width", confidenceWidth + confidenceMargin.left + confidenceMargin.right)
                        .attr("height", confidenceHeight + confidenceMargin.top + confidenceMargin.bottom)
                        .append("g")
                        .attr("transform", `translate(${confidenceMargin.left},${confidenceMargin.top})`);

                    const confidenceX = d3.scaleTime()
                        .domain(d3.extent(confidenceTimeSeries, d => d[0]))
                        .range([0, confidenceWidth]);

                    confidenceSvg.append("g")
                        .attr("transform", `translate(0, ${confidenceHeight})`)
                        .call(d3.axisBottom(confidenceX).tickFormat(d3.timeFormat("%Y-%m-%d")));
                    confidenceSvg.append("text")
                        .attr("x", confidenceWidth / 2)
                        .attr("y", confidenceHeight + confidenceMargin.bottom - 5)
                        .style("text-anchor", "middle")
                        .text("Date");

                    const confidenceY = d3.scaleLinear()
                        .domain([0, 1])
                        .range([confidenceHeight, 0]);

                    confidenceSvg.append("g")
                        .call(d3.axisLeft(confidenceY));
                    confidenceSvg.append("text")
                        .attr("transform", "rotate(-90)")
                        .attr("y", -confidenceMargin.left + 20)
                        .attr("x", -confidenceHeight / 2)
                        .style("text-anchor", "middle")
                        .text("Average Confidence");

                    const confidenceLine = d3.line()
                        .x(d => confidenceX(d[0]))
                        .y(d => confidenceY(d[1]));

                    confidenceSvg.append("path")
                        .datum(confidenceTimeSeries)
                        .attr("fill", "none")
                        .attr("stroke", "orange")
                        .attr("stroke-width", 2)
                        .attr("d", confidenceLine);

                </script>
            <?php
        }

    }

?>