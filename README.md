<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## About the project

Work assignment is a simple app that allow a user to register his information (name, email and phone) and update them
if needed at any time.

## project requirements
- Docker: you need to docker in order to run this project locally

## How to run
- Clone the project
```
git clone https://github.com/revaxl/work_assigment.git
```
- Build the project
```
docker build -t local:work .
```
- Run the newly built image of the project
```
docker run -p 80:80 local:work
```

## License

The project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
