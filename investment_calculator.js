document.addEventListener("DOMContentLoaded", function () {
            
    const defaultOptions = { fertilization: 589.68, OrchardEstablishment: 1200.00,CropManagement: 569.04  };
    const optionalOptions = { irrigation: 0, security: 0, marketing: 0 };

    const mediumCaseYields = [0, 0, 20, 50, 100, 200, 250, 300, 400, 500];
    const worstCaseYields = mediumCaseYields.map(yieldVal => Math.round(yieldVal * 0.7));
    const bestCaseYields = mediumCaseYields.map(yieldVal => Math.round(yieldVal * 1.5));

    function calculateInitialInvestmentAndCosts(sizeAcre, selectedOptions) {
        let initialCost = Object.values(defaultOptions).reduce((a, b) => a + b, 0);

        selectedOptions.forEach(option => {
            if (optionalOptions[option]) initialCost += optionalOptions[option];
        });

        initialCost *= sizeAcre;

        let yearlyOperationCosts = [Math.round(initialCost, 2)];
        let fertilizationCost = 546.00;

        for (let year = 2; year <= 10; year++) {
            fertilizationCost *= 1.05;
            yearlyOperationCosts.push(Math.round(fertilizationCost, 2));
        }

        return { initialCost, yearlyOperationCosts };
    }

    function calculateRevenue(sizeAcre, years, scenario, options) {
        const { initialCost, yearlyOperationCosts } = calculateInitialInvestmentAndCosts(sizeAcre, options);

        let yieldCases = {
            worst: worstCaseYields,
            medium: mediumCaseYields,
            best: bestCaseYields
        };

        let totalRevenue = [];
        let grandTotalRevenue = 0;
        let totalYield = 0;

        for (let year = 1; year <= years; year++) {
            if (year <= 2) {
                totalRevenue.push(0);
            } else {
                let yieldPerTree = yieldCases[scenario][year - 1];
                let revenuePerAcre = sizeAcre * (120 * 0.9) * yieldPerTree * 0.25 * 1;

                let operationalCost = yearlyOperationCosts[year - 1] || 0;
                let netRevenue = Math.max(revenuePerAcre - operationalCost, 0);

                totalRevenue.push(netRevenue);
                totalYield += (yieldPerTree * 0.25);
                grandTotalRevenue += netRevenue;
            }
        }

        let additionalOptionsCount = options.length;
        let userShare = 0.7 - (0.05 * additionalOptionsCount);
        let companyShare = 0.1;
        let investorShare = 0.2 + (0.05 * additionalOptionsCount);

        return {
            totalRevenue: totalRevenue.map(r => r.toFixed(2)),
            userShare: totalRevenue.map(r => (r * userShare).toFixed(2)),
            companyShare: totalRevenue.map(r => (r * companyShare).toFixed(2)),
            investorShare: totalRevenue.map(r => (r * investorShare).toFixed(2)),
            initialInvestment: initialCost.toFixed(2),
            grandTotalRevenue: grandTotalRevenue.toFixed(2),
            grandInvestorShare: (investorShare * grandTotalRevenue).toFixed(2),
            grandFarmerShare: (userShare * grandTotalRevenue).toFixed(2),
            grandCompanyShare: (companyShare * grandTotalRevenue).toFixed(2),
            totalYield: totalYield.toFixed(2)
        };
    }

    function renderResults() {
        let sizeAcre = parseFloat(document.getElementById("sizeAcre").value);
        // let years = parseInt(document.getElementById("years").value);
        let years = parseInt(document.getElementById('yearsSlider').value);
        let scenario = document.getElementById("scenario").value;
        let options = [...document.querySelectorAll('input[name="options"]:checked')].map(el => el.value);

        if (isNaN(sizeAcre) || isNaN(years) || !scenario) return;

        let results = calculateRevenue(sizeAcre, years, scenario, options);

        document.getElementById("results").innerHTML = `
            <h4>Investment Summary</h4>
            <p><strong>Initial Investment:</strong> $${results.initialInvestment}</p>
            <p><strong>Grand Total Revenue:</strong> $${results.grandTotalRevenue}</p>
            <p><strong>Investor Share:</strong> $${results.grandInvestorShare}</p>
            <p><strong>Farmer Share:</strong> $${results.grandFarmerShare}</p>
            <p><strong>Company Share:</strong> $${results.grandCompanyShare}</p>
            <p><strong>Total Yield:</strong> ${results.totalYield} tons</p>
            `;
    }

    document.getElementById("calculateBtn").addEventListener("click", renderResults);
    document.getElementById('yearsSlider').addEventListener('input', function() {
        document.getElementById('yearsLabel').innerText = this.value;
    });
});

