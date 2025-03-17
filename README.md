# Taiwan Political Accounting Reports Collector

A data collection tool for extracting accounting reports from [ardata.cy.gov.tw](https://ardata.cy.gov.tw/), Taiwan's political accounting report disclosure platform.

## Overview

This project systematically collects and processes:
- Annual financial reports from political parties
- Campaign finance reports from individual election candidates

The data is sourced from the Control Yuan's [Political Accounting Reports Public Disclosure Platform](https://ardata.cy.gov.tw/).

## Features

- Automated data collection from ardata.cy.gov.tw
- Structured storage of financial reports
- Data processing for analysis
- Historical archiving of political finance data

## Data Structure

The collected data is organized into the following structure:

```
data/
├── candidates.csv                # Master list of candidates with election and ID information
├── electorals.json              # Electoral district information
├── parties/                     # Political party financial reports
│   ├── page_1.json              # Index of party reports
│   └── account/                 # Party annual financial reports by year
│       ├── 台灣民眾黨/          # Example party folder
│       │   ├── 108.zip          # Reports for year 108 (2019)
│       │   ├── 109.zip          # Reports for year 109 (2020)
│       │   └── ...
│       └── ...                  # Other political parties
│
├── individual/                  # Individual candidate campaign finance reports
│   ├── page_1.json to page_6.json  # Index of individual reports
│   └── account/                 # Organized by election
│       ├── 113年立法委員選舉/    # 2024 Legislative Yuan election
│       │   ├── 臺北市/          # By city/county
│       │   │   ├── 候選人_1.zip # Individual candidate report
│       │   │   └── ...
│       │   └── ...              # Other cities/counties
│       ├── 109年總統、副總統選舉/ # 2020 Presidential election
│       └── ...                  # Other elections
│
└── mirror-media/                # Additional data from Mirror Media investigations
    ├── A02_company_all_public.csv       # Company donations data
    ├── political_donation_7_od.csv      # Political donation data (7th term)
    └── political_donation_8_od.csv      # Political donation data (8th term)
```

### Data Files

1. **Party Financial Reports**: Annual financial disclosures from registered political parties in Taiwan, organized by party name and year.

2. **Individual Campaign Reports**: Campaign finance reports for candidates in various elections, organized by:
   - Election type and year
   - Geographic region (city/county)
   - Individual candidate

3. **Reference Data**:
   - `candidates.csv`: Master list linking candidates to their respective elections and unique IDs
   - `electorals.json`: Detailed information about electoral districts in Taiwan

4. **Mirror Media Data**: Additional political finance data from Mirror Media investigations, including:
   - Company donations to political campaigns
   - Historical political donation records

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Author

Finjon Kiang 