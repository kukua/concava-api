# Getting started

This chapter helps you to get started with the ConCaVa API.

## Step 1: Determine data format

Say you have a device with two sensors:

- Temperature: in degrees Celcius, with one decimal point and between -100.0 and 100.0. E.g. `234`, which will later be divided by 10 to add the decimal point.
- Distance: in centimeters, between 0 and 80. E.g. `15`.

Since some devices do not always have an internet connection, we also add the UNIX timestamp of the moment the measurement occured.

This can be translated into the following format:

- `timestamp => uint32le`
- `temp => int16le`
- `distance => uint8` (no endianess for a single byte)

For example: `20e03e57 ea00 0f` (1463738400, 234, 15)

## Step 2: Register user

```bash
curl -v -XPOST http://<host>/v1/users -H 'Content-Type: application/json' \
	-d '{"name": "John Doe", "email": "john@example.com", "password": "securepassword", "password_confirmation": "securepassword"}'
# {"id": 123, ..., "token": "<auth token>"}
```

Later on, you can again retrieve the token with:

```bash
curl -v http://<host>/v1/users/login -H 'Authorization: Basic <base64 encoded <email>:<password>>'
# {"id": 110, ..., "token": "<auth token>"}
```

## Step 3: Create template

```bash
curl -v -XPOST http://<host>/v1/templates -H 'Content-Type: application/json' -H 'Authorization: Token <auth token>' \
	-d '{"name": "My template"}'
# {"id": 120, ...}
```

## Step 4: Add attributes to template

```bash
curl -v -XPOST http://<host>/v1/attributes -H 'Content-Type: application/json' -H 'Authorization: Token <auth token>' \
	-d '{"template_id": 120, "name": "timestamp", "order": 0, "converter":"uint32le"}'
# {"id": 130, ...}
curl -v -XPOST http://<host>/v1/attributes -H 'Content-Type: application/json' -H 'Authorization: Token <auth token>' \
	-d '{"template_id": 120, "name": "temp", "order": 1, "converter":"int16le", "calibrator": "return value / 10", "validators": "min=-100 max=100"}'
# {"id": 131, ...}
curl -v -XPOST http://<host>/v1/attributes -H 'Content-Type: application/json' -H 'Authorization: Token <auth token>' \
	-d '{"template_id": 120, "name": "distance", "order": 2, "converter":"uint8", "validators": "min=0 max=80"}'
# {"id": 132, ...}
```

## Step 5: Create device

```bash
curl -v -XPOST http://<host>/v1/devices -H 'Content-Type: application/json' -H 'Authorization: Token <auth token>' \
	-d '{"template_id": 120, "name": "My device", "udid": "<device id>"}'
# {"id": 140, ...}
```

## Step 6: Test posting data to ConCaVa

```bash
echo '20e03e57ea000f' | xxd -r -p | \
	curl -i -XPUT 'http://<host>:3000/v1/sensorData/<device id>' \
	-H 'Authorization: Token <auth token>' \
	-H 'Content-Type: application/octet-stream' --data-binary @-
# Response: 200 OK
```
