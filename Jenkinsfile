#!groovy

timestamps {
  node {
    cleanWs()
    checkout scm

    // Code quality tests begins
    try {
      sh '/usr/local/bin/composer install -o'

      stage('PHP CodeSniffer (Linting)') {
        sh '/usr/local/bin/composer run-phpcs'
      }
      stage('PHP Lint (Syntax)') {
        sh '/usr/local/bin/composer run-phplint'
      }
      stage('PHP Mess Detector (Code Format/Complexity)') {
        sh '/usr/local/bin/composer run-phpmd'
      }
      stage('PHPUnit (Unit/Functional)') {
        sh '/usr/local/bin/composer run-phpunit'
      }
    } catch (e) {
       currentBuild.result = 'FAILURE'
    }
    // Code quality tests ends
  }
}
