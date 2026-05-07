**A little "project" for testing purposes**

TODO:
- Use Money class in Operations.php instead of amount/currency params and return values.
- Add get/view methods into Money
- Amounts saved as integer instead of float - refactor all methods, change SQL dumps.
- Refactor ExchangeRate -> ExchangeRateProvider s getRate(from, to) metodou - jeden provider pro jednu banku + kurz.
- Vylepšit Bank/Wallet tak, aby dávaly smysl v rámci obecného Depositu.
- Doladit validace a testy (dělení nulou apod.).
- Přidat konfigurace pro Docker, Composer, CL/CI, pohrát si s AGENTS.md.
