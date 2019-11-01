# Examples for Trusted Shops API SDK for PHP

Note that everything is done from the root of the project and not from the examples folder.

## How to run the examples

1. Install the dependencies with Composer:

```bash
$ composer install
```

2. Copy the `.env.example` file in the examples folder as `.env`

```bash
$ cp examples/.env.example examples/.env
```

3. Fill the `.env` file with correct information

    - `TRUSTEDSHOPS_TSID` is your TrustedShops ID
    - `TRUSTEDSHOPS_LOGIN` is your username for TrustedShops
    - `TRUSTEDSHOPS_PASS` is the password tied to your username

4. Run the PHP built-in web server. Supply the `-t` option to this directory:

```bash
$ php -s localhost:8000 -t examples/
```

5. Point your browser to the host and port you specified.

## How does the TrustedShops API works

The TrustedShops API has 2 APIs :

- Common
- Restricted

The Common API is a public API that doesn't require any credentials.

The Restricted API is a private API that require your credentials, the examples for this API will only work if all the variables in the .env file were filled.
