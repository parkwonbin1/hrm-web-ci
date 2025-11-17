pipeline {
    agent any

    environment {
        DOCKER_ID = 'qkrdnjs1217@gmail.com' 
        IMAGE_NAME = 'hrm-php'
        DOCKER_CRED_ID = 'docker-hub-login'
        SSH_CRED_ID = 'ssh-web-host'
    }

    stages {
        stage('Build Image') {
            steps {
                echo '🔨 1. Docker 이미지 빌드 중...'
                // web 폴더 안의 Dockerfile.php를 사용해서 빌드!
                sh "docker build -t $DOCKER_ID/$IMAGE_NAME:latest -f web/Dockerfile.php ./web"
            }
        }

        stage('Push to Registry') {
            steps {
                echo '🚀 2. Docker Hub에 업로드 중...'
                // 젠킨스에 등록한 ID/PW로 로그인 후 Push
                withCredentials([usernamePassword(credentialsId: DOCKER_CRED_ID, usernameVariable: 'USER', passwordVariable: 'PASS')]) {
                    sh "echo $PASS | docker login -u $USER --password-stdin"
                    sh "docker push $DOCKER_ID/$IMAGE_NAME:latest"
                }
            }
        }

        stage('Deploy with Ansible') {
            steps {
                echo '🚚 3. Ansible로 배포 시작...'
                // Ansible 플러그인을 사용해서 Playbook 실행
                ansiblePlaybook(
                    playbook: 'ansible/deploy.yml',
                    inventory: 'ansible/hosts',
                    credentialsId: SSH_CRED_ID, // SSH 키 자동 주입
                    disableHostKeyChecking: true,
                    colorized: true
                )
            }
        }
    }
}
