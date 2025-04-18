<?php
/*
Plugin Name: Avocado Investment Calculator
Description: A simple avocado investment calculator for WordPress.
Version: 1.0
Author: Your Name
*/

function avocado_calculator_shortcode() {
    ob_start();
    ?>
    <div class="container">
        <div class="form-section">
            <h4>Investment Input</h4>

            <label>Scenario:</label>
            <div class="radio-group">
                <label><input type="radio" name="scenario" value="worst" checked> Conservative</label>
                <label><input type="radio" name="scenario" value="medium"> Realistic</label>
                <label><input type="radio" name="scenario" value="best"> Optimistic</label>
            </div>

            <label for="years">Investment Duration (years): <span id="yearsValue">10</span></label>
            <input type="range" id="years" min="10" max="10" value="10" disabled>

            <div class="row">
                <div class="summary-item">
                    <p><strong>Mature Tree Yield (fruits per tree):</strong> <span id="scenario-yield">--</span></p>
                </div>
                <div class="summary-item">
                    <p><strong>Price per Fruit:</strong> $<span id="scenario-price">0.25</span></p>
                </div>
            </div>
        </div>

        <div class="summary-section">
            <h4>Investment Summary</h4>

            <label for="treesInvested">Trees Invested: <span id="treesInvestedValue">0</span></label>
            <input type="range" id="treesInvested" min="0" max="1000" value="0">
            <p><strong>Money Invested:</strong> $<span id="moneyInvested">0</span></p>

            <label for="farmSize">Farm Size (acres): <span id="farmSizeValue">0</span></label>
            <input type="range" id="farmSize" min="0" max="100" value="0">
            <p><strong>Number of Trees:</strong> <span id="treesPerFarmSize">0</span></p>

            <div class="summary">
                <p><strong>Money Invested:</strong> $<span id="OutputMoneyInvested">0</span></p>
                <p><strong>Orchard Establishment Cost:</strong> $<span id="investment">--</span></p>
                <p><strong>Estimated Revenue:</strong> $<span id="revenue">--</span></p>
                <p><strong>Estimated Profit:</strong> $<span id="profit">--</span></p>
                <p><strong>Return/Year:</strong> $<span id="returnPerYear">--</span></p>
                <p><strong>Carbon Sequestered:</strong> (tonnes) <span id="carbon-sequestered">--</span></p>
            </div>
            <button class="toggle-chart" onclick="toggleChart()">Toggle Revenue Chart</button>
        </div>
    </div>

    <div id="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e6ffe6, #ccffcc);
            color: #2e7d32;
            margin: 0;
            padding: 2rem;
        }
        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 12px;
            padding: 2rem;
            padding-left: 1rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 1100px;
            margin: auto;
            flex-wrap: nowrap;
        }
        .form-section, .summary-section {
            flex: 1 1 50%;
            margin: 1rem;
        }
        h4 {
            margin-top: 0;
            color: #33691e;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 1rem;
        }
        input[type="range"] {
            width: 100%;
        }
        .radio-group {
            display: flex;
            gap: 1rem; /* Add spacing between the radio buttons */
            margin-bottom: 1rem; /* Add spacing below the radio group */
        }
        .radio-group label {
            display: flex;
            align-items: center;
            font-weight: normal;
        }
        .radio-group input {
            margin-right: 0.5rem; /* Add spacing between the radio button and its label */
        }
        .summary {
            background: #f1f8e9;
            padding: 1rem;
            border-radius: 10px;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr; /* Create two equal-width columns */
            gap: 1rem; /* Add spacing between items */
            
        }
        .summary p {
            margin: 0;
            padding: 0.5rem;
            background: #ffffff; /* Add a white background for contrast */
            border-radius: 8px; /* Add rounded edges */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle shadow for separation */
            border: 1px solid #ccc; /* Add a border to separate items */
        }
        .summary span {
            color: #2c0246; /* Apply the distinct color to digits */
            font-weight: bold; /* Make the digits stand out */
        }
        .row {
            display: flex;
            justify-content: space-between; /* Space out the two items */
            gap: 1rem; /* Add spacing between the two items */
            margin-top: 1rem;
            margin-bottom: 1rem; /* Add spacing below the row */
            margin-left: 0.5rem;
            margin-right:0.5rem;
        }
        .row span {
            color: #2c0246; /* Apply the distinct color to digits */
            font-weight: bold; /* Make the digits stand out */
        }
        .summary-item {
            flex: 1; /* Allow items to take equal space */
            background: #f1f8e9; /* Add a white background for contrast */
            border-radius: 8px; /* Add rounded edges */
            padding: 0.5rem 1rem; /* Add padding inside the items */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle shadow for separation */
        }
        .summary-item p {
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        #chart-container {
            width: 100%;
            max-width: 1000px; /* Limit the width of the chart */
            margin: 2rem auto; /* Center the chart */
            display: none; /* Initially hidden */
        }

        #revenueChart {
            max-height: 300px; /* Limit the height of the chart */
            width: 100%; /* Ensure the chart scales to fit the container */
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 1rem;
            }
            .form-section, .summary-section {
                flex: 1 1 100%;
                margin: 0;
                margin-bottom: 1rem;
            }
            .toggle-chart {
                width: 100%;
                text-align: center;
            }
            #chart-container {
                max-width: 100%; /* Allow the chart to take full width on smaller screens */
            }

            #revenueChart {
                max-height: 250px; /* Reduce height for smaller screens */
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            h4 {
                font-size: 1.2rem;
            }
            .summary {
                font-size: 0.85rem;
            }
            .toggle-chart {
                font-size: 0.9rem;
                padding: 0.4rem 0.8rem;
            }
            #revenueChart {
                max-height: 200px; /* Further reduce height for very small screens */
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const farmSizeSlider = document.getElementById('farmSize');
        const treesInvestedSlider = document.getElementById('treesInvested');
        const yearsSlider = document.getElementById('years');
        const scenarioRadios = document.getElementsByName('scenario');

        const farmSizeValue = document.getElementById('farmSizeValue');
        const treesInvestedValue = document.getElementById('treesInvestedValue');
        const yearsValue = document.getElementById('yearsValue');
        const moneyInvestedEl = document.getElementById('moneyInvested');
        const OutputMoneyInvestedEl = document.getElementById('OutputMoneyInvested');

        const revenueEl = document.getElementById('revenue');
        const profitEl = document.getElementById('profit');
        const returnPerYearEl = document.getElementById('returnPerYear');
        // const revenueByTreesEl = document.getElementById('revenueByTrees');
        const yieldEl = document.getElementById('scenario-yield');
        const priceEl = document.getElementById('scenario-price');
        const investmentEl = document.getElementById('investment');

        const defaultOptions = { fertilization: 589.68, OrchardEstablishment: 1200.00, CropManagement: 569.04 };
        const treesPerAcre = 120 * 0.9;

        const mediumCaseYields = [0, 0, 20, 50, 100, 200, 250, 300, 400, 500];
        const worstCaseYields = mediumCaseYields.map(y => Math.round(y * 0.7));
        const bestCaseYields = mediumCaseYields.map(y => Math.round(y * 1.5));

        const sequestrationRates = {
            worst: 260 * 0.5,
            medium: 260 * 0.75,
            best: 260
        };

        farmSizeSlider.value = 0;
        treesInvestedSlider.value = 0;

        let lastSlider = 'farmSize';

        farmSizeSlider.addEventListener('input', () => {
            lastSlider = 'farmSize';
            calculate();
        });

        treesInvestedSlider.addEventListener('input', () => {
            lastSlider = 'treesInvested';
            calculate();
        });

        function calculate() {
            const farmSize = +farmSizeSlider.value;
            const treesInvested = +treesInvestedSlider.value;
            const years = 10;
            const scenario = [...scenarioRadios].find(r => r.checked).value;

            let fruitPrice;
            if (scenario === 'worst') {
                fruitPrice = 0.2;
            } else if (scenario === 'medium') {
                fruitPrice = 0.25;
            } else if (scenario === 'best') {
                fruitPrice = 0.3;
            }

            farmSizeValue.textContent = farmSize;
            treesInvestedValue.textContent = treesInvested;
            yearsValue.textContent = years;
            priceEl.textContent = fruitPrice.toFixed(2);

            // Calculate and display the number of trees based on farm size
            const treesForFarmSize = Math.round(farmSize * treesPerAcre);
            document.getElementById('treesPerFarmSize').textContent = treesForFarmSize;

            // Update OutputMoneyInvested based on the slider chosen
            if (lastSlider === 'treesInvested') {
                OutputMoneyInvestedEl.textContent = (treesInvested * 50).toFixed(2);
            } else if (lastSlider === 'farmSize') {
                OutputMoneyInvestedEl.textContent = (treesForFarmSize * 50).toFixed(2);
            }

            const yieldsMap = {
                worst: worstCaseYields,
                medium: mediumCaseYields,
                best: bestCaseYields
            };
            const yieldArray = yieldsMap[scenario];

            let initialCost;
            let fertilization;

            if (lastSlider === 'treesInvested') {
                initialCost = Object.values(defaultOptions).reduce((a, b) => a + b, 0) / treesPerAcre * treesInvested;
                fertilization = 546.00 / treesPerAcre * treesInvested;
            } else {
                initialCost = Object.values(defaultOptions).reduce((a, b) => a + b, 0) * farmSize;
                fertilization = 546.00;
            }

            let yearlyCosts = [initialCost];

            for (let year = 2; year <= 10; year++) {
                fertilization *= 1.05;
                yearlyCosts.push(Math.round(fertilization, 2));
            }

            let totalRevenue = 0;
            let yearlyRevenue = [];

            for (let year = 1; year <= years; year++) {
                const yieldPerTree = yieldArray[year - 1] || 0;
                let revenue;

                if (lastSlider === 'treesInvested') {
                    revenue = treesInvested * yieldPerTree * fruitPrice;
                } else {
                    revenue = farmSize * treesPerAcre * yieldPerTree * fruitPrice;
                }

                yearlyRevenue.push(revenue);
                totalRevenue += revenue;
            }

            const totalCost = yearlyCosts.slice(0, years).reduce((a, b) => a + b, 0);
            const totalProfit = totalRevenue - totalCost;

            let totalSequestrationRate;
            if (lastSlider === 'treesInvested') {
                totalSequestrationRate = sequestrationRates[scenario] / treesPerAcre * treesInvested;
                const totalCarbonSequestered = totalSequestrationRate * years;
                document.getElementById('carbon-sequestered').textContent = totalCarbonSequestered.toFixed(2);
            } else {
                totalSequestrationRate = sequestrationRates[scenario];
                const totalCarbonSequestered = farmSize * years * totalSequestrationRate;
                document.getElementById('carbon-sequestered').textContent = totalCarbonSequestered.toFixed(2);
            }

            const yieldPerTree = yieldArray[years - 1] || 0;

            moneyInvestedEl.textContent = (treesInvested * 50).toFixed(2);
            revenueEl.textContent = totalRevenue.toFixed(2);
            profitEl.textContent = totalProfit.toFixed(2);
            returnPerYearEl.textContent = (totalProfit / 10).toFixed(2);
            yieldEl.textContent = yieldPerTree;
            investmentEl.textContent = initialCost.toFixed(2);

            updateChart(yearlyRevenue);
        }

        let chartInstance = null;
        function updateChart(revenueData) {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const labels = [...Array(revenueData.length)].map((_, i) => `Year ${i + 1}`);

            if (!chartInstance) {
                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Projected Revenue ($)',
                            data: revenueData,
                            fill: true,
                            backgroundColor: 'rgba(102, 187, 106, 0.2)',
                            borderColor: '#43a047',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            } else {
                chartInstance.data.labels = labels;
                chartInstance.data.datasets[0].data = revenueData;
                chartInstance.update();
            }
        }

        function toggleChart() {
            const container = document.getElementById('chart-container');
            container.style.display = container.style.display === 'none' ? 'block' : 'none';
            if (container.style.display === 'block') calculate();
        }

        calculate();
        scenarioRadios.forEach(r => r.addEventListener('change', calculate));
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('avocado_calculator', 'avocado_calculator_shortcode');